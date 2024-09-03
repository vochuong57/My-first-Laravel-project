<div class="row mb15">
    <div class="col-lg-12 mb10">
        <div class="form-row">
            <label for="" class="control-label text-left">Tên widget: <span class="text-danger">(*)</span></label>
            <input 
            type="text"
            name="name"
            value="{{ old('name', ($widget->name)??'') }}"
            class="form-control"
            placeholder=""
            autocomplete="off"
            >
        </div>
    </div>
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">Từ khóa widget: <span class="text-danger">(*)</span></label>
            <input 
            type="text"
            name="keyword"
            value="{{ old('keyword', ($widget->keyword)??'') }}"
            class="form-control"
            placeholder=""
            autocomplete="off"
            >
        </div>
    </div>
</div>    
     