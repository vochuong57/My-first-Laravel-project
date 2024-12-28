<div class="modal fade" id="findProduct" tabindex="-1" aria-labelledby="findProduct" aria-hidden="true">
    <form action="" class="form create-menu-catalogue" method="post">
        <div class="modal-dialog modal-dialog-promotion">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="findProduct">Chọn sản phẩm</h4>
                    <small class="font-bold text-navy">Chọn sản phẩm sẵn có hoặc tìm kiếm sản phẩm mà bạn mong
                        muốn.</small>
                </div>
                <div class="modal-body">
                    <div class="error form-error mb-10 hidden"></div>
                    <div class="search-model-box">
                        <i class="fa fa-search"></i>
                        <input type="text" class="form-control search-model" placeholder="Tìm kiếm theo tên, mã sản phẩm, SKU...">
                    </div>
                    <div class="search-list">
                        @for($i = 0; $i <= 19; $i++)
                        <div class="search-object-item">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <div class="object-info">
                                    <div class="uk-flex uk-flex-middle">
                                        <input type="checkbox" name="" value="" class="input-checkbox">
                                        <span class="image img-scaledown">
                                            <img src="http://127.0.0.1:8000/userfiles/image/product/240615035944-macbook-air-m1-13-inch-8gb256gb-gray.png" alt="">
                                        </span>
                                        <div class="object-name">
                                            <div class="m0">Macbook Pro sản xuất 2024 phiên bản vàng, 256 gb</div>
                                            <div class="jscode">Mã SP: 1722402829</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="object-extract-info">
                                    <div class="price">Giá: 20.000.000đ</div>
                                    <div class="object-inventory">
                                        <div class="uk-flex uk-flex-middle">
                                            <span class="text-1">Tồn kho:</span>
                                            <span class="text-value">10.000</span>
                                            <span class="text-1 slash">|</span>
                                            <span class="text-value">Có thể bán: 9000</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="create" value="create" class="btn btn-success">Xác nhận</button>
                </div>
            </div>
        </div>
    </form>
</div>