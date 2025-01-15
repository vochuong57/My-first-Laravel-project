<div class="ibox">
    <div class="ibox-title">
        <h5>Thông tin chung</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            @if(!isset($offTitle))
            <div class="col-lg-6">
                <div class="form-row">
                    <label for="" class="control-label text-left">Tên chương trình: <span class="text-danger">(*)</span></label>
                    <input 
                        type="text"
                        name="name"
                        value="{{ old('name', ($model->name)??'') }}"
                        class="form-control"
                        placeholder=""
                        autocomplete="off"
                    >
                </div>
            </div>
            @endif
            <div class="col-lg-6">
                <div class="form-row">
                    <label for="" class="control-label text-left">Mã khuyến mãi: {!! (isset($offTitle)) ? '<span class="text-danger">(*)</span>' : '' !!}</label>
                    <input 
                        type="text"
                        name="code"
                        value="{{ old('code', ($model->code)??'') }}"
                        class="form-control"
                        placeholder="{!! (isset($offTitle)) ? '' : 'Nếu mã khuyến mại để trống hệ thống sẽ tự động tạo' !!}"
                        autocomplete="off"
                    >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">Mô tả khuyến mãi: </label>
                    <textarea name="description" class="form-control form-textare" style="height: 100px;">{{ old('description', $model->description ?? null) }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>