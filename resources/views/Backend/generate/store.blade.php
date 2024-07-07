@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']])
@include('Backend.dashboard.component.formError')
@php
    $url=($config['method']=='create')?route('generate.create'):route('generate.update', $generate->id)
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">{{ __('messages.general') }}</div>
                    <div class="panel-description">
                        <p>{{ __('messages.note_generate') }}</p>
                        <p>{{ __('messages.note_1') }} <span class="text-danger">(*)</span> {{ __('messages.note_2') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('messages.general') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">{{ __('messages.generate_title') }} <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="name"
                                    value="{{ old('name', ($generate->name)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">{{ __('messages.generate_sidebar_module') }} <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="sidebar_module"
                                    value="{{ old('sidebar_module', ($generate->sidebar_module)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">{{ __('messages.generate_moduleType') }} <span class="text-danger">(*)</span></label>
                                    <select name="module_type" id="" class="form-control setupSelect2">
                                        <option value="0">Chọn loại Module</option>
                                        <option value="1">Module danh mục</option>
                                        <option value="2">Module chi tiết</option>
                                        <option value="3">Module khác</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">{{ __('messages.generate_path') }} <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="path"
                                    value="{{ old('path', ($generate->path)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">{{ __('messages.generate_schema') }}</div>
                    <div class="panel-description">
                        <p>{{ __('messages.generate_note_schema') }}</p>
                        <p>{{ __('messages.note_1') }} <span class="text-danger">(*)</span> {{ __('messages.note_2') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('messages.generate_schema') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">{{ __('messages.generate_schema1') }} <span class="text-danger">(*)</span></label>
                                    <textarea 
                                    name="schema"
                                    value=""
                                    class="form-control schema"
                                    >{{ old('schema', ($generate->schema)??'') }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnTitle'] }}</button>
        </div>
    </div>
</form>
