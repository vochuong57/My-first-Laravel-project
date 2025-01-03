<div class="row mb15">
    <div class="col-lg-12 mb10">
        <div class="form-row">
            <label for="" class="control-label text-left">Tên nguồn khách: <span class="text-danger">(*)</span></label>
            <input 
            type="text"
            name="name"
            value="{{ old('name', ($source->name)??'') }}"
            class="form-control"
            placeholder=""
            autocomplete="off"
            >
        </div>
    </div>
    <div class="col-lg-12 mb10">
        <div class="form-row">
            <label for="" class="control-label text-left">Từ khóa nguồn khách: <span class="text-danger">(*)</span></label>
            <input 
            type="text"
            name="keyword"
            value="{{ old('keyword', ($source->keyword)??'') }}"
            class="form-control"
            placeholder=""
            autocomplete="off"
            >
        </div>
    </div>
    <div class="col-lg-12">
        <select name="publish" class="form-control setupSelect2" id="">
            @foreach(__('messages.publish') as $key => $val)
                <option 
                    {{ $key == old('publish', (isset($source->publish)) ? $source->publish : '') ? 'selected' : '' }} 
                    value="{{ $key }}"
                >
                    {{ $val }}
                </option>
            @endforeach
        </select>
    </div>
</div>    
     