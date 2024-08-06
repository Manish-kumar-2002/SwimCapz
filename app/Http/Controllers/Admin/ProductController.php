<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\PriceHelper;
use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\Category;
use App\Models\Childcategory;
use App\Models\Currency;
use App\Models\FeaturedOn;
use App\Models\Gallery;
use App\Models\Generalsetting;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use App\Models\Subcategory;
use App\Models\PricingBreak;
use DB;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Image;
use File;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductController extends AdminBaseController
{

    protected $styleArray;
    public function __construct()
    {
        parent::__construct();
        $this->styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => 'black'),
                ),
            ),
        );
    }

    public function datatables(Request $request)
    {
        $seting=Generalsetting::find(1);
        $datas = Product::with('category', 'variant')
                    ->latest('id');
        return Datatables::of($datas)
            ->editColumn('name', function (Product $data) {
                $name = mb_strlen($data->name, 'UTF-8') > 50 ?
                    mb_substr($data->name, 0, 50, 'UTF-8') . '...' : $data->name;
                $id = '<small>' . __("ID") . ': <a href="#"
                target="_blank">' . sprintf("%'.08d", $data->id) . '</a></small>';
                $id3 = $data->type == 'Physical' ?
                    '<small class="ml-2"> ' . __("SKU") . ': <a href="#"
                target="_blank">' . $data->sku . '</a>' : '';
                return $name . '<br>' . $id . $id3;
            })
            ->editColumn('category', function (Product $data) {
                return $data->category->name;
            })
            ->addColumn('latest_product', function (Product $data) use($seting){
                $selected_class=in_array($data->id, explode(',', $seting->latest_products)) ? 'fa-toggle-on' : 'fa-toggle-off';
                $url=route('admin.latest.product.update', [$data->id]);
                return '<label data-confirm="1" data-url="'.$url.'" class="toggle-checkbox form-check-label" for="toggleSwitch">
                    <i id="toggleIcon" class="fas '.$selected_class.'"></i>
                </label>';
            })
            ->addColumn('status', function (Product $data) {
                $selected_class=$data->status == 1 ? 'fa-toggle-on' : 'fa-toggle-off';
                $url=route('admin-prod-status', ['id1' => $data->id]);
                return '<label data-confirm="1" data-url="'.$url.'" class="toggle-checkbox form-check-label" for="toggleSwitch">
                    <i id="toggleIcon" class="fas '.$selected_class.'"></i>
                </label>';
            })
            ->addColumn('action', function (Product $data) {
                return '<div class="godropdown action-list">
                            <a href="' . route('admin-prod-edit', $data->id) . '">
                                    <i class="fas fa-edit"></i> 
                                </a>
                                <a href="' . route('admin-prod-edit', $data->id) . '?view=true">
                                    <i class="fas fa-eye"></i> 
                                </a>
                                <a
                                    href="javascript:;"
                                    data-href="' . route('admin-prod-delete', $data->id) . '"
                                    data-toggle="modal"
                                    data-target="#confirm-delete"
                                    class="delete"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                        </div>';
            })
            ->rawColumns(['name', 'status', 'action', 'latest_product'])
            ->toJson();
    }

    public function download()
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Product');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);

        $tr = 1;

        $sheet->setCellValue("A$tr", 'Name');
        $sheet->setCellValue("B$tr", 'Category');
        $tr += 1;

        $datas = Product::with('category', 'variant')->latest('id')->get();
        foreach ($datas as $row) {
            $sheet->setCellValue("A$tr", $row->name);
            $sheet->setCellValue("B$tr", @$row->category->name);

            $tr += 1;
        }

        $sheet = $sheet->getStyle('A1:B' . $tr)->applyFromArray($this->styleArray);

        $writer = new Xlsx($spreadsheet);
        $file = 'Product-' . time() . '.xlsx';
        $writer->save($file);

        return response()
            ->download(public_path($file))
            ->deleteFileAfterSend();
    }

    public function catalogdatatables()
    {
        $datas = Product::where('is_catalog', '=', 1)->orderBy('id', 'desc');

        return Datatables::of($datas)
            ->editColumn('name', function (Product $data) {
                $name = mb_strlen($data->name, 'UTF-8') > 50
                    ? mb_substr($data->name, 0, 50, 'UTF-8') . '...' : $data->name;

                $id = '<small>' . __("ID") .
                    ': <a href="' . route('front.product', $data->slug) .
                    '" target="_blank">' . sprintf("%'.08d", $data->id) . '</a></small>';
                $id3 = $data->type == 'Physical'
                    ? '<small class="ml-2"> ' . __("SKU") .
                    ': <a href="' . route('front.product', $data->slug) . '" target="_blank">' . $data->sku . '</a>' : '';
                return $name . '<br>' . $id . $id3;
            })
            ->editColumn('price', function (Product $data) {
                $price = $data->price * $this->curr->value;
                return PriceHelper::showAdminCurrencyPrice($price);
            })
            ->editColumn('stock', function (Product $data) {
                $stck = (string) $data->stock;
                if ($stck == "0") {
                    return __("Out Of Stock");
                } elseif ($stck == null) {
                    return __("Unlimited");
                } else {
                    return $data->stock;
                }
            })
            ->addColumn('status', function (Product $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';
                return '<div class="action-list">
                    <select class="process select droplinks ' . $class . '">
                        <option data-val="1" value="' . route('admin-prod-status', [
                    'id1' => $data->id, 'id2' => 1
                ]) . '" ' . $s . '>' . __("Active") . '</option>
                        <option data-val="0" value="' . route('admin-prod-status', [
                    'id1' => $data->id, 'id2' => 0
                ]) . '" ' . $ns . '>' . __("Inactive") . '</option>
                    </select>
                </div>';
            })
            ->addColumn('action', function (Product $data) {
                return '<div class="godropdown">
                    <button
                        class="go-dropdown-toggle"
                    >  ' . __("Actions") . '<i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="action-list">
                        <a
                            href="' . route('admin-prod-edit', $data->id) . '"
                        >
                            <i class="fas fa-edit"></i> ' . __("Edit") . '
                        </a>
                        <a
                            href="javascript"
                            class="set-gallery"
                            data-toggle="modal"
                            data-target="#setgallery"
                        >
                            <input
                                type="hidden"
                                value="' . $data->id . '"
                            ><i class="fas fa-eye"></i> ' . __("View Gallery") . '
                        </a>
                        <a
                            data-href="' . route('admin-prod-feature', $data->id) . '"
                            class="feature"
                            data-toggle="modal"
                            data-target="#modal2"
                        > <i class="fas fa-star"></i> ' . __("Highlight") . '
                        </a>
                            <a
                                href="javascript:;"
                                data-href="' . route('admin-prod-catalog', [
                    'id1' => $data->id, 'id2' => 0
                ]) . '"
                                data-toggle="modal"
                                data-target="#catalog-modal"
                            ><i class="fas fa-trash-alt"></i> ' . __("Remove Catalog") . '
                        </a>
                    </div>
                </div>';
            })
            ->rawColumns(['name', 'status', 'action'])
            ->toJson();
    }

    public function productscatalog()
    {
        return view('admin.product.catalog');
    }
    public function index()
    {
        return view('admin.product.index');
    }

    public function types()
    {
        return view('admin.product.types');
    }

    public function deactive()
    {
        return view('admin.product.deactive');
    }

    public function productsettings()
    {
        $latestProducts = Product::whereIn('id', explode(',', $this->gs->latest_products))
            ->get();

        $featuredOn = FeaturedOn::whereIn('id', explode(',', $this->gs->featured_on))
            ->get();

        return view('admin.product.settings', compact('latestProducts', 'featuredOn'));
    }

    public function create()
    {
        // $cats = Category::all();
        $cats = Category::where('status', 1)->get();
        $product_type = ProductType::where('status', 1)->get();
        $sign = $this->curr;
        return view('admin.product.create.physical', compact('cats', 'sign', 'product_type'));
    }

    public function status($id1, $id2=null)
    {
        $data = Product::findOrFail($id1);
        $data->status = !$data->status;
        $data->update();
        $msg = __('Status updated successfully.');
        return response()->json($msg);
    }

    public function uploadUpdate(Request $request, $id)
    {

        $rules = [
            'image' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = Product::findOrFail($id);

        $image = $request->image;
        list($type, $image) = explode(';', $image);
        list(, $image) = explode(',', $image);
        $image = base64_decode($image);
        $image_name = time() . Str::random(8) . '.png';
        $path = 'assets/images/products/' . $image_name;
        file_put_contents($path, $image);
        if ($data->photo != null) {
            if (file_exists(public_path() . '/assets/images/products/' . $data->photo)) {
                unlink(public_path() . '/assets/images/products/' . $data->photo);
            }
        }
        $input['photo'] = $image_name;
        $data->update($input);
        if ($data->thumbnail != null) {
            if (file_exists(public_path() . '/assets/images/thumbnails/' . $data->thumbnail)) {
                unlink(public_path() . '/assets/images/thumbnails/' . $data->thumbnail);
            }
        }

        $img = Image::make(public_path() . '/assets/images/products/' . $data->photo)->resize(285, 285);
        $thumbnail = time() . Str::random(8) . '.jpg';
        $img->save(public_path() . '/assets/images/thumbnails/' . $thumbnail);
        $data->thumbnail = $thumbnail;
        $data->update();
        return response()->json(['status' => true, 'file_name' => $image_name]);
    }

    private function manageStoreData(&$input, $request, $data, $sign)
    {
        if ($request->minimum_qty_check == "") {
            $input['minimum_qty'] = null;
        }

        if ($request->shipping_time_check == "") {
            $input['ship'] = null;
        }
        if (!empty($request->product_type)) {
            $input['product_type'] = implode(',', $request->product_type);
        }
        if (empty($request->color_check)) {
            $input['color_all'] = null;
        } else {
            $input['color_all'] = implode(',', $request->color_all);
        }

        if (empty($request->size_check)) {
            $input['size_all'] = null;
        } else {
            $input['size_all'] = implode(',', $request->size_all);
        }

        if (empty($request->seo_check)) {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;
        } else {
            if (!empty($request->meta_tag)) {
                $input['meta_tag'] = implode(',', $request->meta_tag);
            }
        }

        if (!in_array(null, $request->features) && !in_array(null, $request->colors)) {
            $input['features'] = implode(',', str_replace(',', ' ', $request->features));
            $input['colors'] = implode(',', str_replace(',', ' ', $request->colors));
        } else {
            if (in_array(null, $request->features) || in_array(null, $request->colors)) {
                $input['features'] = null;
                $input['colors'] = null;
            } else {
                $features = explode(',', $data->features);
                $colors = explode(',', $data->colors);
                $input['features'] = implode(',', $features);
                $input['colors'] = implode(',', $colors);
            }
        }

        if (!empty($request->tags)) {
            $input['tags'] = implode(',', $request->tags);
        }
        $attrArr = [];
        if (!empty($request->category_id)) {
            $catAttrs = Attribute::where('attributable_id', $request->category_id)
                ->where('attributable_type', 'App\Models\Category')
                ->get();
            if (!empty($catAttrs)) {
                foreach ($catAttrs as $key => $catAttr) {
                    $in_name = $catAttr->input_name;
                    if ($request->has("$in_name")) {
                        $attrArr["$in_name"]["values"] = $request["$in_name"];
                        foreach ($request["$in_name" . "_price"] as $aprice) {
                            $ttt["$in_name" . "_price"][] = $aprice / $sign->value;
                        }
                        $attrArr["$in_name"]["prices"] = $ttt["$in_name" . "_price"];
                        if ($catAttr->details_status) {
                            $attrArr["$in_name"]["details_status"] = 1;
                        } else {
                            $attrArr["$in_name"]["details_status"] = 0;
                        }
                    }
                }
            }
        }

        if ($request->file('size_chart')) {
            $path = public_path('storage/productChart');
            Helper::manageFolder($path);

            $file = $request->file('size_chart');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move($path, $filename);
            $input['size_chart'] = $filename;
        }
    }
    public function store(Request $request)
    {

        $data = new Product;
        $sign = $this->curr;
        $input = $request->all();
        $rules = ['sku' => 'min:8|unique:products'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $this->manageStoreData($input, $request, $data, $sign);
        $data->fill($input)->save();


        $prod = Product::find($data->id);
        $prod->slug = Str::slug($data->name, '-') . '-' . strtolower(Str::random(3) . $data->id . Str::random(3));

        $prod->update();

        return response()->json(array(
            'message' => __("New product added successfully."),
            'redirect' => route('admin-prod-edit', $prod->id) . '?variants=true'
        ));
    }

    public function import()
    {

        $cats = Category::all();
        $sign = $this->curr;
        return view('admin.product.productcsv', compact('cats', 'sign'));
    }

    //*** POST Request
    public function importSubmit(Request $request)
    {
        $log = "";
        //--- Validation Section
        $rules = [
            'csvfile' => 'required|mimes:csv,txt',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $filename = '';
        if ($file = $request->file('csvfile')) {
            $filename = time() . '-' . $file->getClientOriginalExtension();
            $file->move('assets/temp_files', $filename);
        }

        $datas = "";

        $file = fopen(public_path('assets/temp_files/' . $filename), "r");
        $i = 1;

        while (($line = fgetcsv($file)) !== false) {

            if ($i != 1) {

                if (!Product::where('sku', $line[0])->exists()) {
                    //--- Validation Section Ends

                    //--- Logic Section
                    $data = new Product;
                    $sign = Currency::where('is_default', '=', 1)->first();

                    $input['type'] = 'Physical';
                    $input['sku'] = $line[0];

                    $input['category_id'] = null;
                    $input['subcategory_id'] = null;
                    $input['childcategory_id'] = null;

                    $mcat = Category::where(DB::raw('lower(name)'), strtolower($line[1]));
                    //$mcat = Category::where("name", $line[1]);

                    if ($mcat->exists()) {
                        $input['category_id'] = $mcat->first()->id;

                        if ($line[2] != "") {
                            $scat = Subcategory::where(DB::raw('lower(name)'), strtolower($line[2]));

                            if ($scat->exists()) {
                                $input['subcategory_id'] = $scat->first()->id;
                            }
                        }
                        if ($line[3] != "") {
                            $chcat = Childcategory::where(DB::raw('lower(name)'), strtolower($line[3]));

                            if ($chcat->exists()) {
                                $input['childcategory_id'] = $chcat->first()->id;
                            }
                        }

                        $input['photo'] = $line[5];
                        $input['name'] = $line[4];
                        $input['details'] = $line[6];
                        $input['color'] = $line[13];
                        $input['price'] = $line[7];
                        $input['previous_price'] = $line[8] != "" ? $line[8] : null;
                        $input['stock'] = $line[9];
                        $input['size'] = $line[10];
                        $input['size_qty'] = $line[11];
                        $input['size_price'] = $line[12];
                        $input['youtube'] = $line[15];
                        $input['policy'] = $line[16];
                        $input['meta_tag'] = $line[17];
                        $input['meta_description'] = $line[18];
                        $input['tags'] = $line[14];
                        $input['product_type'] = $line[19];
                        $input['affiliate_link'] = $line[20];
                        $input['slug'] = Str::slug($input['name'], '-') . '-' . strtolower($input['sku']);

                        $image_url = $line[5];

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_URL, $image_url);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
                        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_HEADER, true);
                        curl_setopt($ch, CURLOPT_NOBODY, true);

                        $content = curl_exec($ch);
                        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

                        $thumb_url = '';

                        if (strpos($contentType, 'image/') !== false) {
                            $fimg = Image::make($line[5])->resize(800, 800);
                            $fphoto = time() . Str::random(8) . '.jpg';
                            $fimg->save(public_path() . '/assets/images/products/' . $fphoto);
                            $input['photo'] = $fphoto;
                            $thumb_url = $line[5];
                        } else {
                            $fimg = Image::make(public_path() . '/assets/images/noimage.png')->resize(800, 800);
                            $fphoto = time() . Str::random(8) . '.jpg';
                            $fimg->save(public_path() . '/assets/images/products/' . $fphoto);
                            $input['photo'] = $fphoto;
                            $thumb_url = public_path() . '/assets/images/noimage.png';
                        }

                        $timg = Image::make($thumb_url)->resize(285, 285);
                        $thumbnail = time() . Str::random(8) . '.jpg';
                        $timg->save(public_path() . '/assets/images/thumbnails/' . $thumbnail);
                        $input['thumbnail'] = $thumbnail;

                        // Conert Price According to Currency
                        $input['price'] = ($input['price'] / $sign->value);
                        $input['previous_price'] = ($input['previous_price'] / $sign->value);

                        // Save Data
                        $data->fill($input)->save();
                    } else {
                        $log .= "<br>" . __('Row No') . ": " . $i . " - " . __('No Category Found!') . "<br>";
                    }
                } else {
                    $log .= "<br>" . __('Row No') . ": " . $i . " - " . __('Duplicate Product Code!') . "<br>";
                }
            }

            $i++;
        }
        fclose($file);

        //--- Redirect Section
        $msg = __('Bulk Product File Imported Successfully.') . $log;
        return response()->json($msg);
    }


    public function edit($id, Request $request)
    {
        $cats = Category::all();
        $data = Product::findOrFail($id);
        $sign = $this->curr;
        $product_variant = ProductVariant::where('product_id', $id)->get();
        $size = array();
        $color = array();
        $pricing_details = PricingBreak::where('product_id', $id)->get();
        if (isset($data->size_all) && !empty($data->size_all)) {
            $size = explode(',', $data->size_all);
        }
        if (isset($data->color_all) && !empty($data->color_all)) {
            $color = explode(',', $data->color_all);
        }

        $isVarients = $request->has('variants') ? 1 : 0;
        return view('admin.product.edit.physical', compact(
            'cats',
            'data',
            'sign',
            'product_variant',
            'size',
            'color',
            'pricing_details',
            'isVarients'
        ));
    }

    private function supportUpdate($request, &$input, $data, $sign)
    {

        if ($request->minimum_qty_check == "") {
            $input['minimum_qty'] = null;
        }

        if ($request->shipping_time_check == "") {
            $input['ship'] = null;
        }
        if (!empty($request->product_type)) {
            $input['product_type'] = implode(',', $request->product_type);
        }

        if (empty($request->color_check)) {
            $input['color_all'] = null;
        } else {
            $input['color_all'] = implode(',', $request->color_all);
        }

        if (empty($request->size_check)) {
            $input['size_all'] = null;
        } else {
            $input['size_all'] = implode(',', $request->size_all);
        }

        if (empty($request->seo_check)) {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;
        } else {
            if (!empty($request->meta_tag)) {
                $input['meta_tag'] = implode(',', $request->meta_tag);
            }
        }

        if (!in_array(null, $request->features) && !in_array(null, $request->colors)) {
            $input['features'] = implode(',', str_replace(',', ' ', $request->features));
            $input['colors'] = implode(',', str_replace(',', ' ', $request->colors));
        } else {
            if (in_array(null, $request->features) || in_array(null, $request->colors)) {
                $input['features'] = null;
                $input['colors'] = null;
            } else {
                $features = explode(',', $data->features);
                $colors = explode(',', $data->colors);
                $input['features'] = implode(',', $features);
                $input['colors'] = implode(',', $colors);
            }
        }

        if (!empty($request->tags)) {
            $input['tags'] = implode(',', $request->tags);
        }
        if (empty($request->tags)) {
            $input['tags'] = null;
        }

        $attrArr = [];
        if (!empty($request->category_id)) {
            $catAttrs = Attribute::where('attributable_id', $request->category_id)
                ->where('attributable_type', 'App\Models\Category')->get();
            if (!empty($catAttrs)) {
                foreach ($catAttrs as $key => $catAttr) {
                    $in_name = $catAttr->input_name;
                    if ($request->has("$in_name")) {
                        $attrArr["$in_name"]["values"] = $request["$in_name"];
                        foreach ($request["$in_name" . "_price"] as $aprice) {
                            $ttt["$in_name" . "_price"][] = $aprice / $sign->value;
                        }
                        $attrArr["$in_name"]["prices"] = $ttt["$in_name" . "_price"];
                        if ($catAttr->details_status) {
                            $attrArr["$in_name"]["details_status"] = 1;
                        } else {
                            $attrArr["$in_name"]["details_status"] = 0;
                        }
                    }
                }
            }
        }


        if (empty($attrArr)) {
            $input['attributes'] = null;
        } else {
            $jsonAttr = json_encode($attrArr);
            $input['attributes'] = $jsonAttr;
        }

        $data->slug = Str::slug($data->name, '-') . '-' . strtolower($data->sku);

        if ($request->file('size_chart')) {
            $path = public_path('storage/productChart');
            Helper::manageFolder($path);

            $file = $request->file('size_chart');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move($path, $filename);
            $input['size_chart'] = $filename;
        }
    }
    public function update(Request $request, $id)
    {
        $data = Product::findOrFail($id);
        $sign = $this->curr;
        $input = $request->all();

        $rules = ['sku' => 'min:8|unique:products,sku,' . $id];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $this->supportUpdate($request, $input, $data, $sign);
        $data->update($input);

        return response()->json(array(
            'message' => __("Product Updated Successfully."),
            'redirect' => route('admin-prod-edit', $data->id)
        ));
    }

    //*** GET Request
    public function feature($id)
    {
        $data = Product::findOrFail($id);
        return view('admin.product.highlight', compact('data'));
    }

    //*** POST Request
    public function featuresubmit(Request $request, $id)
    {
        //-- Logic Section
        $data = Product::findOrFail($id);
        $input = $request->all();
        if ($request->featured == "") {
            $input['featured'] = 0;
        }
        if ($request->hot == "") {
            $input['hot'] = 0;
        }
        if ($request->best == "") {
            $input['best'] = 0;
        }
        if ($request->top == "") {
            $input['top'] = 0;
        }
        if ($request->latest == "") {
            $input['latest'] = 0;
        }
        if ($request->big == "") {
            $input['big'] = 0;
        }
        if ($request->trending == "") {
            $input['trending'] = 0;
        }
        if ($request->sale == "") {
            $input['sale'] = 0;
        }
        if ($request->is_discount == "") {
            $input['is_discount'] = 0;
            $input['discount_date'] = null;
        } else {
            $input['discount_date'] = \Carbon\Carbon::parse($input['discount_date'])->format('Y-m-d');
        }

        $data->update($input);
        //-- Logic Section Ends

        //--- Redirect Section
        $msg = __('Highlight Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends

    }

    //*** GET Request
    public function destroy($id)
    {

        $data = Product::findOrFail($id);
        if ($data->galleries->count() > 0) {
            foreach ($data->galleries as $gal) {
                if (file_exists(public_path() . '/assets/images/galleries/' . $gal->photo)) {
                    unlink(public_path() . '/assets/images/galleries/' . $gal->photo);
                }
                $gal->delete();
            }
        }

        if ($data->variant->count() > 0) {
            foreach ($data->variant as $gal) {
                $gal->delete();
            }
        }
        if ($data->carts->count() > 0) {
            foreach ($data->carts as $gal) {
                $gal->delete();
            }
        }

        if ($data->reports->count() > 0) {
            foreach ($data->reports as $gal) {
                $gal->delete();
            }
        }

        if ($data->ratings->count() > 0) {
            foreach ($data->ratings as $gal) {
                $gal->delete();
            }
        }
        if ($data->wishlists->count() > 0) {
            foreach ($data->wishlists as $gal) {
                $gal->delete();
            }
        }
        if ($data->clicks->count() > 0) {
            foreach ($data->clicks as $gal) {
                $gal->delete();
            }
        }
        if ($data->comments->count() > 0) {
            foreach ($data->comments as $gal) {
                if ($gal->replies->count() > 0) {
                    foreach ($gal->replies as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }

        if (!filter_var($data->photo, FILTER_VALIDATE_URL)) {
            if ($data->photo) {
                if (file_exists(public_path() . '/assets/images/products/' . $data->photo)) {
                    unlink(public_path() . '/assets/images/products/' . $data->photo);
                }
            }
        }

        if (file_exists(public_path() . '/assets/images/thumbnails/' . $data->thumbnail) && $data->thumbnail != "") {
            unlink(public_path() . '/assets/images/thumbnails/' . $data->thumbnail);
        }

        if ($data->file != null) {
            if (file_exists(public_path() . '/assets/files/' . $data->file)) {
                unlink(public_path() . '/assets/files/' . $data->file);
            }
        }
        $data->delete();
        //--- Redirect Section
        $msg = __('Product Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends

        // PRODUCT DELETE ENDS
    }


    public function catalog($id1, $id2)
    {
        $data = Product::findOrFail($id1);
        $data->is_catalog = $id2;
        $data->update();
        if ($id2 == 1) {
            $msg = "Product added to catalog successfully.";
        } else {
            $msg = "Product removed from catalog successfully.";
        }
        return response()->json($msg);
    }

    public function latestProductUpdate($id)
    {
        $data = Generalsetting::findOrFail(1);
        $availProduct=explode(',', $data->latest_products);
        if(in_array($id,$availProduct)) {
            $availProduct = array_filter($availProduct, function($value) use ($id) {
                return $value !== $id;
            });
        } else {
            $availProduct[]=$id;
        }

        $input['latest_products'] = implode(',', $availProduct);
        cache()->forget('generalsettings');

        $data->update($input);
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
    }

    public function settingUpdate(Request $request)
    {
        //--- Logic Section
        $input = $request->all();
        $data = \App\Models\Generalsetting::findOrFail(1);

        if (!empty($request->product_page)) {
            $input['product_page'] = implode(',', $request->product_page);
        } else {
            $input['product_page'] = null;
        }

        if (!empty($request->wishlist_page)) {
            $input['wishlist_page'] = implode(',', $request->wishlist_page);
        } else {
            $input['wishlist_page'] = null;
        }

        if (!empty($request->latest_products)) {
            $input['latest_products'] = implode(',', $request->latest_products);
        } else {
            $input['latest_products'] = null;
        }

        if (!empty($request->featured_on)) {
            $input['featured_on'] = implode(',', $request->featured_on);
        } else {
            $input['featured_on'] = null;
        }

        cache()->forget('generalsettings');

        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function getAttributes(Request $request)
    {
        $model = '';
        if ($request->type == 'category') {
            $model = 'App\Models\Category';
        } elseif ($request->type == 'subcategory') {
            $model = 'App\Models\Subcategory';
        } elseif ($request->type == 'childcategory') {
            $model = 'App\Models\Childcategory';
        }

        $attributes = Attribute::where('attributable_id', $request->id)->where('attributable_type', $model)->get();
        $attrOptions = [];
        foreach ($attributes as $key => $attribute) {
            $options = AttributeOption::where('attribute_id', $attribute->id)->get();
            $attrOptions[] = ['attribute' => $attribute, 'options' => $options];
        }
        return response()->json($attrOptions);
    }



    public function getCrossProduct($catId)
    {
        $crossProducts = Product::where('category_id', $catId)->where('status', 1)->get();
        return view('load.cross_product', compact('crossProducts'));
    }

    public function variantStore(Request $request)
    {
        $this->validate($request, [
            'size'             => 'required',
            'color_code'       => 'required',
            'price'            => 'required|numeric',
            'images'           => 'required|array',
            'images.*'         => 'image|mimes:jpeg,png,jpg|max:2048',
            'quantity'         => 'required|integer',
            'front_image'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'back_image'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
            //'discount_price'   => 'required|numeric|lt:price',
            'minimum_order'    => 'required|integer',
            'default'          => 'required',
            'back_image_overlay' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'front_image_overlay' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        $img_count = ProductVariantImage::where('variant_id', $request->variant_id)->where('product_id', $request->product_id)->count();
        if (!$request->hasFile('images') &&  $img_count < 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Upload Images are required',
                'records' => []
            ]);
        }

        $color = ProductVariant::where('color_code', $request->color_code)
            ->where('size', $request->size)
            ->where('product_id', $request->prod_id)
            ->exists();

        if ($color) {

            $this->validate($request, [
                'other' => 'required'
            ], ['other.required' => "Color and size already exist"]);
        }

        try {
            DB::beginTransaction();

            $makeDefault = 2;
            if (@$request->default == "1") {
                ProductVariant::where('product_id', $request->prod_id)
                    ->update(['default' => 2]);
                $makeDefault = 1;
            } else {
                $total = ProductVariant::where('product_id', $request->prod_id)
                    ->count();
                if ($total <= 0) {
                    $makeDefault = 1;
                }
            }

            $input = $request->all();
            $input['product_id'] = $request->prod_id;
            $input['default']    = $makeDefault;

            $this->manageVariantStoreImages($input, $request);

            $data = new ProductVariant;
            $save = $data->create($input);
            DB::commit();

            // Store multiple images
            if ($request->hasFile('images')) {
                $images = $request->file('images');

                foreach ($images as $image) {
                    $imageName = rand(10000, 9999999) . '-' . time() . '.' . $image->getClientOriginalExtension();

                    // Image
                    $imagePath = public_path() . '/assets/product/' . $imageName;
                    Image::make($image)->save($imagePath);

                    // Small
                    $smallPath = public_path() . '/assets/product/thumb/small/' . $imageName;
                    Image::make($image)->resize(285, 285)->save($smallPath);

                    // Medium
                    $mediumPath = public_path() . '/assets/product/thumb/medium/' . $imageName;
                    Image::make($image)->resize(300, 300)->save($mediumPath);

                    // Large
                    $largePath = public_path() . '/assets/product/thumb/large/' . $imageName;
                    Image::make($image)->resize(1000, 1000)->save($largePath);

                    $productImg['variant_id'] = $save->id;
                    $productImg['product_id'] = $request->prod_id;
                    $productImg['images'] = $imageName;
                    ProductVariantImage::create($productImg);
                }
            }

            $res = ProductVariant::find($save->id);

            $data = [
                'variant_id' => $res->id,
                'default' => $res->default,
                'product_id' => $res->product_id,
                'color' => $res->color_code,
                'size' =>  $res->size,
                'price' =>  $res->price,
                'quantity' =>  $res->quantity,
            ];
            return response()->json([
                'status' => 'success', 'message' => 'Variant has been added successfully',
                'records' => $data
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error', 'message' => $e->getMessage(), 'records' => []
            ]);
        }
    }

    private function manageVariantStoreImages(&$input, $request)
    {
        $basePathFront = '/assets/product/front/';
        $basePathBack = '/assets/product/back/';


        if ($request->hasFile('front_image')) {
            $frontImage = $request->file('front_image');
            $frontImageName = rand(10000, 9999999) . '-' . time() . '.' . $frontImage->getClientOriginalExtension();
            $frontImagePath = public_path() . $basePathFront . $frontImageName;

            Image::make($frontImage)->save($frontImagePath);
            $input['front_image'] = $frontImageName;
        }

        if ($request->hasFile('back_image')) {
            $backImage = $request->file('back_image');
            $backImageName = rand(10000, 9999999) . '-' . time() . '.' . $backImage->getClientOriginalExtension();
            $backImagePath = public_path() . $basePathBack . $backImageName;

            Image::make($backImage)->save($backImagePath);
            $input['back_image'] = $backImageName;
        }

        if ($request->hasFile('front_image_overlay')) {
            $frontImage = $request->file('front_image_overlay');
            $frontImageName = rand(10000, 9999999) . '-' . time() . '.' . $frontImage->getClientOriginalExtension();
            $frontImagePath = public_path() . $basePathFront . $frontImageName;

            Image::make($frontImage)->save($frontImagePath);

            $input['front_image_overlay'] = $frontImageName;
        }

        if ($request->hasFile('back_image_overlay')) {
            $backImage = $request->file('back_image_overlay');
            $backImageName = rand(10000, 9999999) . '-' . time() . '.' . $backImage->getClientOriginalExtension();
            $backImagePath = public_path() . $basePathBack . $backImageName;

            Image::make($backImage)->save($backImagePath);
            $input['back_image_overlay'] = $backImageName;
        }
    }
    public function variantShow(Request $request, $id, $variant_id)
    {
        try {
            $product = Product::findOrFail($id);
            $size = array();
            $color = array();
            if (isset($product->size_all) && !empty($product->size_all)) {
                $size = explode(',', $product->size_all);
            }
            if (isset($product->color_all) && !empty($product->color_all)) {
                $color = explode(',', $product->color_all);
            }
            $data = ProductVariant::with('variantImages')->find($variant_id);
            return view(
                'admin.product.edit.edit_variant',
                compact('data', 'size', 'color', 'product')
            );
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'records' => []]);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'records' => []]);
        }
    }

    public function variantUpdate(Request $request)
    {
        $this->validate($request, [
            'size'    => 'required',
            'color_code'   => 'required',
            'price'   => 'required|numeric',
            'images'  => 'array', // Change 'image' to 'images' and make it an array
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048', // Validation for each image
            'quantity' => 'required|integer',
            'default'  => 'required',
            'front_image'      => 'image|mimes:jpeg,png,jpg|max:2048',
            'back_image'       => 'image|mimes:jpeg,png,jpg|max:2048',
            // 'discount_price'   => 'required|numeric|lt:price',
            'minimum_order'    => 'required|integer',
        ]);
        $img_count = ProductVariantImage::where('variant_id', $request->variant_id)->where('product_id', $request->product_id)->count();
        if (!$request->hasFile('images') &&  $img_count < 1) {
            return response()->json([
                'status' => 'error',
                // 'message' => 'Upload Images are required',
                'message' => 'Upload images field is required',
                'records' => []
            ]);
        }

        $color = ProductVariant::where('color_code', $request->color_code)
            ->where('size', $request->size)
            ->where('id', '!=', $request->variant_id)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($color) {
            return response()->json([
                'status' => 'error',
                'message' => 'Color and size already exist',
                'records' => []
            ]);
        }

        try {
            $makeDefault = 2;
            if (@$request->default == "1") {
                ProductVariant::where('product_id', $request->product_id)
                    ->update(['default' => 2]);
                $makeDefault = 1;
            } else {
                $total = ProductVariant::where('product_id', $request->product_id)
                    ->where('id', '!=', $request->variant_id)
                    ->where('default', 1)
                    ->count();
                if ($total <= 0) {
                    $makeDefault = 1;
                }
            }

            $variant = ProductVariant::findOrFail($request->variant_id);
            $input['color_code']          = $request->color_code;
            $input['size']                = $request->size;
            $input['price']               = $request->price;
            $input['minimum_order']       = $request->minimum_order;
            $input['discount_price']      = $request->discount_price;
            $input['default']             = $makeDefault;
            $input['quantity']            = $request->quantity;

            $this->manageUpdateImages($input, $request, $variant);

            $variant->update($input);

            return response()->json([
                'status' => 'success',
                'message' => 'Variants has been successfully updated', 'records' => []
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'records' => []]);
        }
    }

    private function manageUpdateImages(&$input, $request, $variant)
    {
        if ($request->hasFile('front_image')) {
            $oldFrontImage = public_path() . '/assets/product/front/' . $variant->front;

            if (File::exists($oldFrontImage)) {
                File::delete($oldFrontImage);
            }
            $frontImage = $request->file('front_image');
            $frontImageName = rand(10000, 9999999) . '-' . time() . '.' . $frontImage->getClientOriginalExtension();
            $frontImagePath = public_path() . '/assets/product/front/' . $frontImageName;

            // Save the front image using the Intervention Image library
            Image::make($frontImage)->save($frontImagePath);
            $input['front_image'] = $frontImageName;
        }

        if ($request->hasFile('back_image')) {
            $oldBackImage = public_path() . '/assets/product/back/' . $variant->back_image;

            if (File::exists($oldBackImage)) {
                File::delete($oldBackImage);
            }
            $backImage = $request->file('back_image');
            $backImageName = rand(10000, 9999999) . '-' . time() . '.' . $backImage->getClientOriginalExtension();
            $backImagePath = public_path() . '/assets/product/back/' . $backImageName;

            // Save the back image using the Intervention Image library
            Image::make($backImage)->save($backImagePath);

            // You can save the path to the database or perform other actions as needed
            $input['back_image'] = $backImageName; // Store the filename, not the full path
        }

        if ($request->hasFile('front_image_overlay')) {
            $oldFrontImage = public_path() . '/assets/product/front/' . $variant->front_image_overlay;

            if (File::exists($oldFrontImage)) {
                File::delete($oldFrontImage);
            }
            $frontImage = $request->file('front_image_overlay');
            $frontImageName = rand(10000, 9999999) . '-' . time() . '.' . $frontImage->getClientOriginalExtension();
            $frontImagePath = public_path() . '/assets/product/front/' . $frontImageName;

            Image::make($frontImage)->save($frontImagePath);
            $input['front_image_overlay'] = $frontImageName;
        }

        if ($request->hasFile('back_image_overlay')) {
            $oldBackImage = public_path() . '/assets/product/back/' . $variant->back_image_overlay;

            if (File::exists($oldBackImage)) {
                File::delete($oldBackImage);
            }
            $backImage = $request->file('back_image_overlay');
            $backImageName = rand(10000, 9999999) . '-' . time() . '.' . $backImage->getClientOriginalExtension();
            $backImagePath = public_path() . '/assets/product/back/' . $backImageName;

            Image::make($backImage)->save($backImagePath);

            // You can save the path to the database or perform other actions as needed
            $input['back_image_overlay'] = $backImageName; // Store the filename, not the full path
        }

        if ($request->hasFile('images')) {
            $images = $request->file('images');

            foreach ($images as $image) {
                $imageName = rand(10000, 9999999) . '-' . time() . '.' . $image->getClientOriginalExtension();

                // Image
                $imagePath = public_path() . '/assets/product/' . $imageName;
                Image::make($image)->save($imagePath);

                // Small
                $smallPath = public_path() . '/assets/product/thumb/small/' . $imageName;
                Image::make($image)->resize(285, 285)->save($smallPath);

                // Medium
                $mediumPath = public_path() . '/assets/product/thumb/medium/' . $imageName;
                Image::make($image)->resize(300, 300)->save($mediumPath);

                // Large
                $largePath = public_path() . '/assets/product/thumb/large/' . $imageName;
                Image::make($image)->resize(1000, 1000)->save($largePath);
                $productImg['variant_id'] = $request->variant_id;
                $productImg['product_id'] = $request->product_id;
                $productImg['images'] = $imageName;
                ProductVariantImage::create($productImg);
            }
        }
    }
    public function variantDelete(Request $request, $id, $variant_id)
    {

        $product = Product::findOrFail($id);

        $variant = ProductVariant::select('id')->where('id', $variant_id)->where('product_id', $id)->first();

        if (!$variant) {
            return response()->json(['status' => 'error', 'message' => 'Variant is not exists.']);
        }

        try {
            $data = ProductVariant::where('id', $variant_id)->delete();
            $data = ProductVariantImage::where('variant_id', $variant_id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Variant has been deleted successfully', 'variant_id' => $variant_id]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'records' => []]);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'records' => []]);
        }
    }

    public function varianImageDelete(Request $request, $variant_id)
    {
        try {
            $imageName = $request->old_image;
            $variant_delete_image = ProductVariantImage::where('variant_id', $variant_id)->where('images', $imageName)->delete();
            $oldImagePath = public_path() . '/assets/product/' . $imageName;
            $oldSmallPath = public_path() . '/assets/product/thumb/small/' . $imageName;
            $oldMediumPath = public_path() . '/assets/product/thumb/medium/' . $imageName;
            $oldLargePath = public_path() . '/assets/product/thumb/large/' . $imageName;
            // Delete the old images
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            if (File::exists($oldSmallPath)) {
                File::delete($oldSmallPath);
            }

            if (File::exists($oldMediumPath)) {
                File::delete($oldMediumPath);
            }

            if (File::exists($oldLargePath)) {
                File::delete($oldLargePath);
            }
            return response()->json(['status' => 'success', 'message' => 'Variant Image has been deleted successfully', 'variant_id' => $variant_id]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'records' => []]);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'records' => []]);
        }
    }

    public function showProductDetails($id)
    {
        $cats = Category::all();
        $data = Product::findOrFail($id);
        $sign = $this->curr;
        $product_variant = ProductVariant::where('product_id', $id)->get();
        $size = array();
        $color = array();
        //pricing details
        $pricing_details = PricingBreak::where('product_id', $id)->get();
        if (isset($data->size_all) && !empty($data->size_all)) {
            $size = explode(',', $data->size_all);
        }
        if (isset($data->color_all) && !empty($data->color_all)) {
            $color = explode(',', $data->color_all);
        }
        return view('admin.product.show', compact('cats', 'data', 'sign', 'product_variant', 'size', 'color', 'pricing_details'));
    }

    public function pricingBreakStore(Request $request)
    {
        $this->validate($request, [
            'quantity' => 'required',
            'prod_id' => 'required',
            'color_1' => 'required|numeric',
            'color_2' => 'required|numeric',
            'color_3' => 'required|numeric',
            'color_4' => 'required|numeric',
        ]);

        if (!isset($request->id)) {
            // Check if the same quantity already exists for the same product
            $existingPricingBreak = PricingBreak::where('quantity', $request->quantity)
                ->where('product_id', $request->prod_id)
                ->first();

            if ($existingPricingBreak) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This quantity already exists for the same product.',
                    'records' => [],
                ]);
            }

            try {
                DB::beginTransaction();
                $input = $request->all();
                $input['product_id'] = $request->prod_id;
                $data = new PricingBreak;
                $save = $data->create($input);
                DB::commit();

                $res = PricingBreak::find($save->id);

                $data = [
                    'id' => $res->id,
                    'quantity' => $res->quantity,
                    'color_1' => $res->color_1,
                    'color_2' => $res->color_2,
                    'color_3' => $res->color_3,
                    'color_4' => $res->color_4,
                    'prod_id' => $res->product_id,
                ];
                return response()->json([
                    'status' => 'success',
                    'message' => 'Variant has been added successfully',
                    'records' => $data,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'records' => [],
                ]);
            } catch (\Throwable $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'records' => [],
                ]);
            }
        } else {
            // Update the existing record based on the ID
            $id = $request->id;
            $input = $request->all();
            $input['product_id'] = $request->prod_id;

            // Check if the same quantity already exists for the same product, excluding the current record
            $existingPricingBreak = PricingBreak::where('quantity', $request->quantity)
                ->where('product_id', $request->prod_id)
                ->where('id', '!=', $id)
                ->first();

            if ($existingPricingBreak) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This quantity already exists for the same product.',
                    'records' => [],
                ]);
            }

            $record = PricingBreak::find($id);
            if ($record) {
                try {
                    DB::beginTransaction();
                    $record->update($input);
                    DB::commit();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Variant has been updated successfully',
                        'records' => $record,
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                        'records' => [],
                    ]);
                } catch (\Throwable $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                        'records' => [],
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Variant not found',
                ], 404);
            }
        }
    }

    // public function pricingBreakShow()
    // {

    // }

    public function pricingBreakDelete($id)
    {
        try {
            $data = PricingBreak::find($id);
            $data->delete();
            return response()->json(['status' => 'success', 'message' => 'Pricing break has been deleted successfully', 'id' => $id]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'records' => []]);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'records' => []]);
        }
    }
}
