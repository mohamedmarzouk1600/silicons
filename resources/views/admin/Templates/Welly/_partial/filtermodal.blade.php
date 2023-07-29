<div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{__('Filter')}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            {!! Form::open(['onsubmit'=>'filterFunction($(this));return false;', 'class'=>"m-form m-form--fit m-form--label-align-right"]) !!}
            <div class="modal-body">
                {!! $slot !!}
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-link m-btn">
                    {{__('Reset Form')}}
                </button>
                <button type="button" class="btn btn-secondary m-btn" data-dismiss="modal">
                    {{__('Close')}}
                </button>
                <button type="submit" class="btn btn-brand m-btn">
                    {{__('Filter')}}
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
