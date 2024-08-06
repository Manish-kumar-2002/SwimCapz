@if ($order_status == Helper::ORDER_PENDING)

    <select
        data-selected="{{$order_status}}" class="form-control" name="status" id="status">
        <option value="{{ $order_status }}" selected>
            {{ Helper::ORDER_PENDING_MSG }}
        </option>
    </select>

@elseif ($order_status == Helper::ORDER_SUCCESS)

    <select
        data-selected="{{Helper::ORDER_SUCCESS}}"
        class="form-control track-text" name="status" id="status">
        <option value="{{ Helper::ORDER_SUCCESS }}" selected>
            {{ Helper::ORDER_SUCCESS_MSG }}
        </option>
        <option value="{{ Helper::ORDER_ACCEPTED }}">
            {{ Helper::ORDER_ACCEPTED_MSG }}
        </option>
        <option value="{{ Helper::ORDER_REJECTED }}">
            {{ Helper::ORDER_REJECTED_MSG }}
        </option>
    </select>
    
@elseif ($order_status == Helper::ORDER_ACCEPTED)

    <select
        data-selected="{{Helper::ORDER_ACCEPTED}}"
        class="form-control track-text" name="status" id="status">
        <option value="{{ Helper::ORDER_ACCEPTED }}" selected>
            {{ Helper::ORDER_ACCEPTED_MSG }}
        </option>
        <option value="{{ Helper::ORDER_SHIPPED }}">
            {{ Helper::ORDER_SHIPPED_MSG }}
        </option>
        <option value="{{ Helper::ORDER_CANCELLED }}">
            {{ Helper::ORDER_CANCELLED_MSG }}
        </option>
    </select>

@elseif ($order_status == Helper::ORDER_REJECTED)

    <select
        data-selected="{{Helper::ORDER_REJECTED}}"
        class="form-control" name="status" id="status">
        <option value="{{ Helper::ORDER_REJECTED }}" selected>
            {{ Helper::ORDER_REJECTED_MSG }}
        </option>
    </select>

@elseif ($order_status == Helper::ORDER_CANCELLED)

    <select
        data-selected="{{Helper::ORDER_CANCELLED}}"
        class="form-control" name="status" id="status">
        <option value="{{ Helper::ORDER_CANCELLED }}" selected>
            {{ Helper::ORDER_CANCELLED_MSG }}
        </option>
    </select>

@elseif ($order_status == Helper::ORDER_SHIPPED)

    <select
        data-selected="{{Helper::ORDER_SHIPPED}}"
        class="form-control track-text" name="status" id="status">
        <option value="{{ Helper::ORDER_SHIPPED }}" selected>
            {{ Helper::ORDER_SHIPPED_MSG }}
        </option>
        <option value="{{ Helper::ORDER_COMPLETED }}">
            {{ Helper::ORDER_COMPLETED_MSG }}
        </option>
        <option value="{{ Helper::ORDER_CANCELLED }}">
            {{ Helper::ORDER_CANCELLED_MSG }}
        </option>
    </select>

@elseif ($order_status == Helper::ORDER_COMPLETED)

    <select
        data-selected="{{Helper::ORDER_COMPLETED}}"
        class="form-control" name="status" id="status">
        <option value="{{ Helper::ORDER_COMPLETED }}" selected>
            {{ Helper::ORDER_COMPLETED_MSG }}
        </option>
    </select>

@endif