<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Models\TraitModel\OrderTrait;
use PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class Order extends Model
{
    use OrderTrait;

    const ORDER_PENDING = 0;
    const ORDER_SUCCESS = 1;
    const ORDER_ACCEPTED = 2;
    const ORDER_REJECTED = 3;
    const ORDER_SHIPPED = 4;
    const ORDER_COMPLETED = 5;
    const ORDER_CANCELLED = 6;

    const ORDER_PENDING_MSG = 'Order Pending';
    const ORDER_SUCCESS_MSG = 'Ongoing Orders';
    const ORDER_ACCEPTED_MSG = 'Order Accepted';
    const ORDER_REJECTED_MSG = 'Order Rejected';
    const ORDER_SHIPPED_MSG = 'Shipped Orders';
    const ORDER_COMPLETED_MSG = 'Completed Orders';
    const ORDER_CANCELLED_MSG = 'Cancelled';

    const PAYMENT_PENDING = 0;
    const PAYMENT_SUCCESS = 1;
    const PAYMENT_REFUND = 2;

    const PAYMENT_PENDING_MSG = 'UnPaid';
    const PAYMENT_SUCCESS_MSG = 'Paid';
    const PAYMENT_REFUND_MSG = 'Refund';

    const PAYMENT_METHOD_PO = 'po';
    const PAYMENT_METHOD_STRIPE = 'stripe';

    protected $appends = ['order_custom_status', 'payment_custom_status', 'cancel_note'];
    protected $fillable = [
        'order_number', 'name', 'email', 'user_id', 'method', 'pay_amount', 'shipping_cost', 'shipping_name',
        'shipping_email', 'shipping_phone', 'shipping_company', 'shipping_address',
        'shipping_appartment', 'shipping_city', 'shipping_zip', 'shipping_state',
        'shipping_country', 'billing_name', 'billing_email', 'billing_phone', 'billing_company',
        'billing_address', 'billing_appartment', 'billing_city', 'billing_zip', 'billing_state',
        'billing_country', 'order_note', 'coupon_code', 'coupon_discount', 'currency_sign',
        'currency_name', 'currency_value', 'order_status', 'payment_status', 'coupon_code', 'coupon_discount',
        'stripe_payment_method', 'stripe_id', 'stripe_customer', 'stripe_status',
        'po_order', 'attachement', 'refund_id', 'refund_amount', 'refund_date',
        'tracking_id', 'tracking_number', 'tracking_slug', 'zip_file_name'
    ];

    public $orderStatus = [];
    public $orderPaymentStatus = [];
    public function __construct()
    {
        $this->setOrderStatus();
        $this->setOrderPaymentStatus();
    }

    public function getAttachementAttribute($value)
    {
        if ($value) {
            return asset('storage/orders/po/' . $value);
        }

        return $value;
    }
    public function details()
    {
        return $this->hasMany(OrderDetails::class, 'order_id', 'id');
    }

    public function vendororders()
    {
        return $this->hasMany('App\Models\VendorOrder', 'order_id');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification', 'order_id');
    }

    public function tracks()
    {
        return $this->hasMany('App\Models\OrderTrack', 'order_id');
    }

    public function trackingDetails()
    {
        return $this->hasMany(TrackingDetail::class, 'order_id', 'courierId');
    }

    /* billing country */
    public function billingCountry()
    {
        return $this->hasOne(Country::class, 'id', 'billing_country');
    }

    /* billing state */
    public function billingState()
    {
        return $this->hasOne(State::class, 'id', 'billing_state');
    }

    /* billing country */
    public function shippingCountry()
    {
        return $this->hasOne(Country::class, 'id', 'shipping_country');
    }

    /* billing state */
    public function shippingState()
    {
        return $this->hasOne(State::class, 'id', 'shipping_state');
    }

    public function updateOrder(array $response)
    {
        $this->update($response);
    }

    public function productDetails()
    {
        return $this->hasManyThrough(
            OrderDetailsDesc::class,
            OrderDetails::class,
            'order_id',
            'desc_id',
            'id',
            'id'
        );
    }

    public function getOrderCount($status)
    {
        if ($status == 6) {
            return Order::where('order_status', $status)->orWhere('order_status', 3)
                ->count();
        } elseif ($status == 1) {
            return Order::where('order_status', $status)->orWhere('order_status', 2)
                ->count();
        } else {
            return Order::where('order_status', $status)
                ->count();
        }
    }

    public function orderTrack()
    {
        return $this->hasMany(OrderTrack::class, 'order_id', 'id');
    }

    public function getIncludedItemCount()
    {
        $totalItem = 0;
        foreach ($this->details as  $details) {
            $totalItem += $details->details()->count();
        }

        return $totalItem;
    }

    public function prepareZipFile()
    {
        $basePath = public_path('storage/preparedOrder');
                $path = $basePath . '/' . $this->id;
        Helper::manageFolder($path);
        
        $orderFolder = $path . '/packing-' . $this->id;
        Helper::manageFolder($orderFolder);
        
        $zipPath = $basePath . '/zipped';
        Helper::manageFolder($zipPath);
        
        /* slip generate */
        $this->slipGenerate($path);
        
        /* Work order */
        $this->workOrderGenerate($path);
        
        /* folder fill */
        $this->zipFolderFill($orderFolder);
        
        /* convert into zip */
        $zipFileName = $this->id . '-archive.zip';
        $zip = new ZipArchive;
        if ($zip->open($zipPath . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
        $this->addFolderToZip($path, $zip);
        $zip->close();

        $this->update([
        'zip_file_name' => $zipFileName
        ]);
        }
    }
    private function addFolderToZip($folder, $zip, $folderInZip = '')
    {
        $files = File::allFiles($folder);
        $dirs = File::directories($folder);

        // Add directories first
        foreach ($dirs as $dir) {
            $relativePath = $folderInZip . '/' . File::basename($dir) . '/';
            $zip->addEmptyDir($relativePath);
            $this->addFolderToZip($dir, $zip, $relativePath);
        }

        // Add files
        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $relativePath = $folderInZip . '/' . $file->getRelativePathname();
            $zip->addFile($filePath, $relativePath);
        }
    }

    public function slipGenerate($path)
    {
        $order = $this;
        $pdf = PDF::loadView('admin.order.zipfile.slip', compact('order'));
        $pdf->setOptions(['dpi' => 72]);
        $pdf->save($path . '/PackingSlip_' . $this->id . '.pdf');
    }

    public function workOrderGenerate($path)
    {  
        $order = $this->getDesignOfOrder();
        $pdf = Pdf::loadView('admin.order.zipfile.work', compact('order'));
        $pdf->setOptions(['dpi' => 72]);
                $pdf->save($path . '/workOrder_' . $this->id . '.pdf');
    }

    public function zipFolderFill($path)
    {
        $back = $path . '/back';
        Helper::manageFolder($back);

        $front = $path . '/front';
        Helper::manageFolder($front);

        $this->generateResourse($front, $back, $this);
    }

    private function generateResourse($front, $back, $order)
    {
        $result = $order->productDetails;
        foreach ($result as $design) {

            $raw_design = $design->raw_design;
            if (array_key_exists('coordinate', $raw_design)) {
                $this->processData($raw_design['coordinate'], $front, $back);
            }
        }
    }

    public function processData($raw_design, $front, $back)
    {
        /* front */
        if (array_key_exists('front', $raw_design)) {
            $aDetails = [];
            foreach ($raw_design['front'] as $key => $obj) {
                $fileName = $this->details()->first()->design_name;
                $fileName = str_replace(' ', '-', $fileName) . '_' . $key;

                $_path = "preparedOrder/" . $this->id . "/packing-" . $this->id . "/front/";
                $this->generateSvg($_path, $obj['image'], $fileName);
                $aDetails[$key]['img'] = $obj['image'];
                $obje = $obj['object'] ;
                $aDetails[$key]['height'] = $this->convert300($obje['height']) ;
                $aDetails[$key]['width'] = $this->convert300($obje['width']) ;
                $aDetails[$key]['top'] = $this->convert300($obje['top']);
                $aDetails[$key]['left'] = $this->convert300($obje['left']);
            }

            /* generate pdf */
            $pdf = Pdf::loadView('admin.order.zipfile.front', compact('aDetails'));
            $pdf->setOptions([
                'dpi' => 300,
                'defaultPaperSize' => 'a4', // Default paper size
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);
            /**
             * pdf size is 6" * 5"
             * 1 inch=300 dpi
             * 1 inch=2.54
             * 6=6/2.54 * 300=709
             * 5=5/2.54 * 300=591
             *
             */
            $h = 15.24 / 2.54 * 72;
            $w = 12.7 / 2.54 * 72;

            $pdf->setPaper(array(0, 0, $h, $w), 'landscape');
            $pdf->save($front . '/printReady.pdf');
        }

        /* back */
        if (array_key_exists('back', $raw_design)) {
            $aDetails = [];
            foreach ($raw_design['back'] as $key => $obj) {
                $fileName = $this->details()->first()->design_name;
                $fileName = str_replace(' ', '-', $fileName) . '_' . $key;

                $_path = "preparedOrder/" . $this->id . "/packing-" . $this->id . "/back/";
                $this->generateSvg($_path, $obj['image'], $fileName);
                $aDetails[$key]['img'] = $obj['image'];
                $obje = $obj['object'] ;
                $aDetails[$key]['height'] = $this->convert300($obje['height']);
                $aDetails[$key]['width'] = $this->convert300($obje['width']);
                $aDetails[$key]['top'] = $this->convert300($obje['top']);
                $aDetails[$key]['left'] = $this->convert300($obje['left']);
            }

            /* generate pdf */
            $pdf = Pdf::loadView('admin.order.zipfile.front', compact('aDetails'));
            $pdf->setOptions([
                'dpi' => 300,
                'defaultPaperSize' => 'a4', // Default paper size
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);
            /**
             * pdf size is 6" * 5"
             * 1 inch=300 dpi
             * 1 inch=2.54
             * 15.24=15.24/2.54 * 300=709
             * 12.7=12.7/2.54 * 300=591
             *
             */

            $h = 15.24 / 2.54 * 72;
            $w = 12.7 / 2.54 * 72;

            $pdf->setPaper(array(0, 0, $h, $w), 'landscape');
            $pdf->save($back . '/printReady.pdf');
        }
    }


    public function convert300($size)
    {
        $calculate = 300 / 72;
        return $size * $calculate;
    }
    public function generateSvg($path, $base64, $fileName)
    {
        if (strpos($base64, 'data:image/svg+xml;base64,') === 0) {
            $base64 = str_replace('data:image/svg+xml;base64,', '', $base64);
        }

        if (strpos($base64, 'data:image/png;base64,') === 0) {
            $base64 = str_replace('data:image/png;base64,', '', $base64);
        }

        // Decode the base64 data
        $svgContent = base64_decode($base64);
        // Define the path to save the SVG file
        $filePath = $path . $fileName . '.svg';

        // Save the SVG file
        Storage::disk('public')
            ->put($filePath, $svgContent);
        return asset('storage/' . $filePath);
    }

    public function getDesignOfOrder()
    {
        $designs = $this->details()->pluck('design_name')->toArray();

        $all_designs = SaveDesign::whereIn('name', $designs);

        return $all_designs;
    }
}
