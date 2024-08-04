<div class="modal fade" id="createMenuCatalogue" tabindex="-1" aria-labelledby="createMenuCatalogue" aria-hidden="true">
  <form action="" class="form create-menu-catalogue" method="post">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title fs-5" id="createMenuCatalogue">Thêm mới vị trí hiển thị của menu</h4>
          <small class="font-bold">Nhập đầy đủ thông tin để tạo vị hiển thị của menu.</small>
        </div>
        <div class="modal-body">
              <div class="error form-error mb-10 hidden"></div>

              <div class="row">
                  <div class="col-lg-12 mb10">
                      <label for="">Tên vị trí hiển thị</label>
                      <input type="text" class="form-control" value="" name="name">
                      <div class="error name"></div>
                  </div>
                  <div class="col-lg-12 mb10">
                      <label for="">Từ khóa</label>
                      <input type="text" class="form-control" value="" name="keyword">
                      <div class="error keyword"></div>
                  </div>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="create" value="create" class="btn btn-primary">Lưu lại</button>
        </div>
      </div>
    </div>
  </form>
</div>