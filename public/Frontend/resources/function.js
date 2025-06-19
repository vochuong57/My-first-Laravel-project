(function($) {
	"use strict";
	var HT = {}; // Khai báo là 1 đối tượng
	var timer;
	var $carousel = $(".owl-slide");

	HT.swiperOption = (setting) => {
        // console.log(setting);
        let option = {};
        if (setting.animation.length) {
            option.effect = setting.animation;
        }
        if (setting.arrow === 'accept') {
            option.navigation = {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            };
        }
        if (setting.autoplay === 'accept') {
            option.autoplay = {
                delay: parseInt(setting.animationDelay) || 2000,
                disableOnInteraction: false,
            };
        }
        if (setting.navigate === 'dots') {
            option.pagination = {
                el: '.slide-swiper-pagination',
            };
        }
        if (setting.width) {
            option.width = parseInt(setting.width);
        }
        if (setting.height) {
            option.height = parseInt(setting.height);
        }
        if (setting.pauseHover === 'accept') {
            option.autoplay = option.autoplay || {};
            option.autoplay.pauseOnMouseEnter = true;
        }
        if (setting.animationSpeed) {
            option.speed = parseInt(setting.animationSpeed);
        }
        return option;
    };
	
	/* MAIN VARIABLE */
	HT.swiper = () => {
		if($('.panel-slide').length){
			let setting = JSON.parse($('.panel-slide').attr('data-setting'))
			let option = HT.swiperOption(setting)
			console.log(option);
			var swiper = new Swiper(".panel-slide .slide-swiper-container", option);
		}
		
	}

	HT.popupSwipperSlide = () => {
		if($('.popup-swiper-container').length){
		var swiperThumbs = new Swiper('.popup-gallery .popup-swiper-container-thumbs', {
			// loop: true,
			spaceBetween: 10,
			slidesPerView: 4,
			// centeredSlides: true,
			slideToClickedSlide: true,
		});

		var swiperMain = new Swiper('.popup-gallery .popup-swiper-container', {
			// loop: true,
			effect: 'fade',
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			autoplay: {
				delay: 4000,
				disableOnInteraction: false,
				pauseOnMouseEnter: true,
			},
			pagination: {
				el: '.swiper-pagination',
			},
			thumbs: {
				swiper: swiperThumbs,
			}
		});
		}
	}

	HT.categorySwipperSlide = () => {
		if($('.category-swiper-container').length){
		var swiperMain = new Swiper('.panel-body .category-swiper-container', {
			loop: false,
			pagination: {
				el: '.swiper-pagination',
			},
			spaceBetween: 20,
			slidesPerView: 1,
			breakpoints: {
				415: {
					slidesPerView: 1,
				},
				500: {
				  slidesPerView: 2,
				},
				768: {
				  slidesPerView: 3,
				},
				1280: {
					slidesPerView: 10,
				}
			},
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			
		});
		}
	}

	// Hàm khởi tạo Swiper sau khi mở popup
	HT.openPopupAndInitializeSwiper = () => {
		// Gắn sự kiện click cho phần tử mở popup
		$('.addcart').on('click', function() {
			// Mở popup
			$('#popup').fadeIn();

			HT.popupSwipperSlide();  // Gọi hàm khởi tạo Swiper sau khi popup đã mở
		});
	};


	HT.carousel = () => {
		$carousel.each(function(){
			let _this = $(this);
			let option = _this.find('.owl-carousel').attr('data-owl');
			let owlInit = atob(option);
			owlInit = JSON.parse(owlInit);
			_this.find('.owl-carousel').owlCarousel(owlInit);
		});
		
	} 

	HT.bestsellerSwiperSlide = () => {
		if($('.bestseller-swipper-container').length){
		var swiper = new Swiper(".panel-bestseller .bestseller-swipper-container", {
			loop: false,
			pagination: {
				el: '.swiper-pagination',
			},
			spaceBetween: 20,
			slidesPerView: 2,
			breakpoints: {
				415: {
					slidesPerView: 1,
				},
				500: {
				  slidesPerView: 2,
				},
				768: {
				  slidesPerView: 3,
				},
				1280: {
					slidesPerView: 4,
				}
			},
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			
		});
	}
	}

	// HT.swiperProject = () => {
	// 	var swiper = new Swiper(".panel-project .swiper-container", {
	// 		loop: false,
	// 		pagination: {
	// 			el: '.swiper-pagination',
	// 		},
	// 		spaceBetween: 20,
	// 		slidesPerView: 2,
	// 		breakpoints: {
	// 			415: {
	// 				slidesPerView: 1,
	// 			},
	// 			500: {
	// 			  slidesPerView: 2,
	// 			},
	// 			768: {
	// 			  slidesPerView: 2,
	// 			},
	// 			1280: {
	// 				slidesPerView: 3,
	// 			}
	// 		},
	// 		navigation: {
	// 			nextEl: '.swiper-button-next',
	// 			prevEl: '.swiper-button-prev',
	// 		},
			
	// 	});
	// }

	// HT.swiperVideo = () => {
	// 	var swiper = new Swiper(".panel-video .swiper-container", {
	// 		loop: false,
	// 		pagination: {
	// 			el: '.swiper-pagination',
	// 		},
	// 		spaceBetween: 20,
	// 		slidesPerView: 2,
	// 		breakpoints: {
	// 			415: {
	// 				slidesPerView: 1,
	// 			},
	// 			500: {
	// 			  slidesPerView: 2,
	// 			},
	// 			768: {
	// 			  slidesPerView: 2,
	// 			},
	// 			1280: {
	// 				slidesPerView: 4,
	// 			}
	// 		},
	// 		navigation: {
	// 			nextEl: '.swiper-button-next',
	// 			prevEl: '.swiper-button-prev',
	// 		},
			
	// 	});
	// }
	
	HT.wow = () => {
		var wow = new WOW(
			{
			  boxClass:     'wow',      // animated element css class (default is wow)
			  animateClass: 'animated', // animation css class (default is animated)
			  offset:       0,          // distance to the element when triggering the animation (default is 0)
			  mobile:       true,       // trigger animations on mobile devices (default is true)
			  live:         true,       // act on asynchronously loaded content (default is true)
			  callback:     function(box) {
				// the callback is fired every time an animation is started
				// the argument that is passed in is the DOM node being animated
			  },
			  scrollContainer: null,    // optional scroll container selector, otherwise use window,
			  resetAnimation: true,     // reset animation on end (default is true)
			}
		  );
		  wow.init();


	}// arrow function

	HT.niceSelect = () => {
		if($('.nice-select').length){
			$('.nice-select').niceSelect();
		}
		
	}

	// HT.openPreviewVideo = () => {
	// 	$('.choose-video').on('click', function(e){
	// 		e.preventDefault()
	// 		let _this = $(this)
	// 		let option = {
	// 			id: _this.attr('data-id')
	// 		}
	// 		$.ajax({
	// 			url: 'ajax/post/video', 
	// 			type: 'GET', 
	// 			data: option, 
	// 			dataType: 'json', 
	// 			beforeSend: function() {
					
	// 			},
	// 			success: function(res) {
	// 				$('.video-preview .video-play').html(res.html)
	// 				$('.video-preview video').attr('autoplay', 'autoplay');
	// 				$('.video-preview iframe').attr('src', function (i, val) {
	// 					return val + (val.indexOf('?') !== -1 ? '&' : '?') + 'autoplay=1';
	// 				});
	// 			},
	// 		});

	// 	})
	// }

	HT.selectProductVariant = (popup) => {
		if($('.choose-attribute').length){
			$(document).on('click', '.choose-attribute', function(e){
				e.preventDefault()
				let _this = $(this)
				let attribute_id = _this.attr('data-attributeid')
				console.log(attribute_id)
				let attribute_name = _this.text()
				_this.parents('.attribute-item').find('span').html(attribute_name)
				_this.parents('.attribute-value').find('.choose-attribute').removeClass('active')
				_this.addClass('active')
				HT.handleAttribute(popup)
			})
		}
	}

	HT.handleAttribute = (popup) => {
		let attribute_id = []
		let flag = true
		$('.attribute-value .choose-attribute').each(function(){
			let _this = $(this)
			if(_this.hasClass('active')){
				attribute_id.push(_this.attr('data-attributeid'))
			}
		})
		// console.log(attribute_id)

		let count = 0;
		$('.attribute').each(function() {
			let attributeItems = $(this).find('.attribute-item');  // Tìm tất cả các .attribute-item trong .attribute
			count += attributeItems.length;
		})
		// console.log(count)
		// console.log($('.attribute .attribute-item').length)

		if(attribute_id.length < count || count == 0){
			flag = false
			return false
		}

		if(flag){
			$.ajax({
				url: 'ajax/product/loadVariant',
				type: 'GET',
				data: {
					'attribute_id': attribute_id,
					'product_id': $('input[name=product_id]').val(),
					'language_id': $('input[name=language_id]').val(),
				},
				dataType: 'json',
				beforeSend: function (){
	
				},
				success: function(res){
					console.log(res)
					let album = res.variant.album.split(',')
					console.log(album)
					if(album != ''){
						HT.setupVariantGallery(album)
					}else{
						//CASE detail page
						if (typeof originalAlbum !== 'undefined') {
							console.log(originalAlbum)
							HT.setupVariantGallery(originalAlbum)
						}else{// CASE popup
							console.log('CASE popup');
							let originalAlbum = $('.originalAlbum').val().trim()
							console.log(originalAlbum);
							originalAlbum = JSON.parse(originalAlbum)
							HT.setupVariantGallery(originalAlbum)
						}
					}
					HT.setupVariantName(res.variant.languages[0].pivot.name)
					HT.setupVariantPrice(res.variantPrice, popup)
					if(!popup){
						HT.setupVariantUrl(attribute_id)
					}
				}
			})
		}
	}

	HT.setupVariantGallery = (album) => {
		// console.log(album);
		
		if(album.length != 0){
			if(Array.isArray(album)){
				let html = 
				`
				<div class="swiper-container popup-swiper-container">
					<div class="swiper-button-next"></div>
					<div class="swiper-button-prev"></div>
					<div class="swiper-wrapper big-pic">`
						album.forEach((val, key) => {
							html += `<div class="swiper-slide">
										<a  class="image img-cover"><img src="${val}" alt="${val}"></a>
									</div>`
						})

			html +=`</div>
					<div class="swiper-pagination"></div>
					</div>
					<div class="swiper-container-thumbs popup-swiper-container-thumbs">
						<div class="swiper-wrapper pic-list">`
							album.forEach((val, key) => {
								html += `<div class="swiper-slide">
											<span  class="image img-cover"><img src="${val}" alt="${val}"></span>
										</div>`
							})
				html+=`</div>
					</div>`
				
				$('.popup-gallery').html(html)
				HT.popupSwipperSlide()
			}else{
				let html = 
				`
				<div class="big-pic">
					<div class="">
						<a class="image img-cover"><img src="${album}" alt="${album}"></a>
					</div>
				</div>
				<div class=""></div>
				`
				$('.popup-gallery').html(html)
			}
		}else{
			let html =
			`
			<div class="">
				<div class="big-pic">
					<div class="">
						<a class="image img-cover"><img src="Backend/img/not-found.png"></a>
					</div>
				</div>
				<div class=""></div>
			</div>`
			$('.popup-gallery').html(html)
		}
	}

	HT.setupVariantName = (name) => {
		// console.log(name)
		let productName = $('.productName').val()
		console.log(productName)
		let productVariantName = productName + ' - ' + name
		$('.title span').text(productVariantName)
	}

	HT.setupVariantPrice = (price, popup) => {
		console.log(price)
		if(!popup){
			if(price.promotion){
				$('.price-sale').text(price.priceSale)
				$('.price-old').text(price.priceOld)
			}else{
				$('.price-sale').text(price.priceOld)
				$('.price-old').text('')
			}
		}else{
			if(price.promotion){
				$('.popup-price-sale').text(price.priceSale)
				$('.popup-price-old').text(price.priceOld)
			}else{
				$('.popup-price-sale').text(price.priceOld)
				$('.popup-price-old').text('')
			}
		}
	}

	HT.loadProductVariant = () => {
		let url = new URL(window.location.href);
		let attributeParam = url.searchParams.get('attribute_id');
	
		if (!attributeParam) {
			// Không có attribute_id → Chọn mặc định phần tử đầu tiên
			if ($('.choose-attribute').length) {
				$('.attribute-item').each(function() {
					let _this = $(this);
					let firstAttribute = _this.find('.choose-attribute').first();
	
					if (firstAttribute.length > 0) {
						firstAttribute.addClass('active')
						let attribute_name = firstAttribute.text()
						_this.find('span').html(attribute_name)
					}
				});
			}
		} else {
			// Có attribute_id → Tách thành mảng và xử lý từng giá trị
			let attributeIds = attributeParam.split(',');
			// console.log(attributeIds);
			
			$('.attribute-item').each(function() {
				let _this = $(this)
	
				_this.find('.choose-attribute').each(function() {
					let attrId = $(this).attr('data-attributeid')
	
					if (attributeIds.includes(attrId)) {
						$(this).addClass('active')
						let attribute_name = $(this).text();
						_this.find('span').html(attribute_name);
					}
				})
			});
		}
	};

	HT.setupVariantUrl = (attribute_id) => {
		console.log(attribute_id);
		let queryString = '?attribute_id=' + attribute_id.join(',')
		// console.log(queryString)
		let urlParams = new URLSearchParams(window.location.search);
		let page = urlParams.get('page');
		if (page) {
			queryString += '&page=' + page;
		}
		let productCanonical = $('.productCanonical').val()
		// console.log(productCanonical)
		productCanonical = productCanonical + queryString
		console.log(productCanonical)

		let stateObject = { attribute_id: attribute_id };
		history.pushState(stateObject, "Page Title", productCanonical);
	}

	HT.updateQuantity = () => {
		// Xử lý sự kiện khi nhấn vào nút plus
		$(document).on('click', '.quantitybox .plus', function() {
			let $input = $(this).siblings('.quantity-text');
			let currentValue = parseInt($input.val());
	
			// Tăng giá trị lên 1
			$input.val(currentValue + 1);
		});
	
		// Xử lý sự kiện khi nhấn vào nút minus
		$(document).on('click', '.quantitybox .minus', function() {
			let $input = $(this).siblings('.quantity-text');
			let currentValue = parseInt($input.val());
	
			// Giảm giá trị xuống 1 nhưng không được nhỏ hơn 1
			if (currentValue > 1) {
				$input.val(currentValue - 1);
			}
		});
	};

	HT.detailShowPopup = () => {
		$(document).on('click', '.detailShowPopup', function() {
			let _this = $(this)
			let product_id = _this.attr('data-id')
			let option = {
				product_id: product_id,
			}
			$.ajax({
				url: 'ajax/product/detailShowPopup',
				type: 'GET',
				data: option,
				dataType: 'json',
				beforeSend: function (){
	
				},
				success: function(res){
					console.log(res)
					HT.showPopup(res.product); // Render popup nội dung trước

					$('#popup').fadeIn(); // Hiển thị popup
					HT.popupSwipperSlide()
					

					let popup = true
					HT.loadProductVariant()
					HT.selectProductVariant(popup)
					HT.handleAttribute(popup)
				}
			})
		})
	}

	HT.renderPopup = (product) => {
		let album = JSON.parse(product.album);
		console.log(album);
		let title = product.languages[0].pivot.name;
		let author = product.users.name;
		let price = product.price.replace(/\.00$/, '');
		let description = product.languages[0].pivot.description;

		let attributeHTML = '';
		if (product.attributeInfo && product.attributeInfo.length > 0) {
			product.attributeInfo.forEach(attibuteCatalogue => {
				let attributeCatalogueName = attibuteCatalogue.languages[0].pivot.name;
				let attributes = attibuteCatalogue.attributes;

				if (attributes && attributes.length > 0) {
					attributeHTML += `
					<div class="attribute-item attribute-color">
						<div class="label">${attributeCatalogueName}: <span></span></div>
						<div class="attribute-value">`;

					attributes.forEach(attribute => {
						let attributeName = attribute.languages[0].pivot.name;
						attributeHTML += `<a class="choose-attribute" title="${attributeName}" data-attributeid="${attribute.id}">${attributeName}</a>`;
					});

					attributeHTML += `</div></div>`;
				}
			});
			attributeHTML += `<input type="hidden" name="product_id" value="${product.id}">
			<input type="hidden" name="language_id" value="${product.languages[0].id}">`;
		}

		let html = `
		<div id="popup" class="uk-modal popupProduct">
			<div class="uk-modal-dialog modal-width-1138">
				<a class="uk-modal-close uk-close"></a>
				<div class="popup-container">
					<div class="panel-body">
						<div class="uk-grid uk-grid-medium">
							<div class="uk-width-large-1-2">
								<div class="popup-gallery">
									<div class="swiper-container popup-swiper-container">
										<div class="swiper-button-next"></div>
										<div class="swiper-button-prev"></div>
										<div class="swiper-wrapper big-pic">`;
	
		let image = product.image ?? 'Backend/img/not-found.png';
		if(album.length > 0){
			album.forEach((val) => {
				html += `
				<div class="swiper-slide">
					<a class="image img-cover"><img src="${val.trim()}" alt=""></a>
				</div>`;
			});
		}else{
			html += `
			<div class="swiper-slide">
				<a class="image img-cover"><img src="${image}" alt=""></a>
			</div>`;
		}
	
		html += `
										</div>
										<div class="swiper-pagination"></div>
									</div>
									<div class="swiper-container-thumbs popup-swiper-container-thumbs">
										<div class="swiper-wrapper pic-list">`;
	
		if (album.length > 0) {
			album.forEach((val) => {
				html += `
				<div class="swiper-slide">
					<span class="image img-cover"><img src="${val.trim()}" alt=""></span>
				</div>`;
			});
		} else {
			html += `
			<div class="swiper-slide">
				<span class="image img-cover"><img src="Backend/img/not-found.png" alt=""></span>
			</div>`;
		}
	
		html += `
										</div>
									</div>
								</div>
							</div>
							<div class="uk-width-large-1-2">
								<div class="popup-product">
									<h2 class="title"><span>${title}</span></h2>
									<input type="hidden" class="productName" value="${ title }">
									<input type="hidden" class="originalAlbum" value='${ album.length != 0 ? JSON.stringify(album) : image }'>
									<div class="rating">
										<div class="uk-flex uk-flex-middle">
											<div class="author">By ${author}</div>
											<div class="star">`;
	
		for (let i = 0; i < 5; i++) {
			html += `<i class="fa fa-star"></i>`;
		}
	
		html += `
											</div>
											<div class="rate-number">(65 reviews)</div>
										</div>
									</div>
									<div class="price">
										<div class="uk-flex uk-flex-bottom">
											<div class="popup-price-sale">${addCommas(price)}đ</div>
											<div class="popup-price-old">${product.priceOld}đ</div>
										</div>
									</div>
									<div class="description">${description}</div>`
									html += `<div class="attribute">${attributeHTML}</div>`;
									html += `
									<div class="quantity">
										<div class="text">Quantity</div>
										<div class="uk-flex uk-flex-middle">
											<div class="quantitybox uk-flex uk-flex-middle">
												<div class="minus quantity-button">-</div>
												<input type="text" class="quantity-text" value="1">
												<div class="plus quantity-button">+</div>
											</div>
											<div class="btn-group uk-flex uk-flex-middle">
												<div class="btn-item btn-1 addToCart" data-id="${product.id}"><a href="#" title="">Add To Cart</a></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>`;
	
		return html;
	};
	
	HT.showPopup = (product) => {
		let popupHTML = HT.renderPopup(product);
	
		// Kiểm tra phần footer
		let $footer = $('.footer');
		if ($footer.length === 0) {
			console.error('Không tìm thấy phần tử .footer');
			return;
		}
	
		// Xóa popup cũ nếu tồn tại
		$('#popup').remove();
	
		// Chèn popup mới render ngay sau footer
		$footer.after(popupHTML);
	
		// Đợi UIkit khởi tạo rồi mở popup
		setTimeout(() => UIkit.modal('#popup').show(), 100);
	};

	HT.observePopup = () => {
		let observer = new MutationObserver(function () {
			let popup = $('#popup');
			if (popup.length && popup.css('display') === 'none') {
				popup.remove();
				console.log('Popup đã bị ẩn và đã xóa.');
			}
		});
	
		observer.observe(document.body, {
			childList: true,        // Theo dõi khi có phần tử con thay đổi
			subtree: true,          // Kiểm tra tất cả phần tử con
			attributes: true,       // Theo dõi thay đổi thuộc tính
			attributeFilter: ['style'] // Chỉ theo dõi thuộc tính `style`
		});
	}
	
	HT.select2=()=>{
        if($('.setupSelect2').length){
            $('.setupSelect2').select2();
        }
    }

	$(document).ready(function(){
		HT.wow()
		// HT.swiperProject()
		// HT.swiperVideo()
		
		/* CORE JS */
		HT.swiper()
		HT.popupSwipperSlide()
		HT.openPopupAndInitializeSwiper()
		HT.categorySwipperSlide()
		HT.bestsellerSwiperSlide()
		HT.niceSelect()		
		HT.carousel()

		HT.loadProductVariant()
		HT.selectProductVariant()
		HT.handleAttribute()

		HT.updateQuantity()

		HT.detailShowPopup()
		HT.observePopup()

		HT.select2()
		// HT.openPreviewVideo()
		// let variants =[
		// 	[
		// 		{ "Dung lượng": "8" },
		// 		{ "Dung lượng": "14" }
		// 	],
		// 	[
		// 		{ "Màu sắc": "4" },
		// 		{ "Màu sắc": "12" }
		// 	]
		// ]
		
		
		// variants = variants.reduce((a, b) => 
		// 	a.flatMap(d => b.map(e => ({ ...d, ...e })))
		// );
		// console.log(variants)
	});

})(jQuery);



addCommas = (nStr) => { 
    nStr = String(nStr);
    nStr = nStr.replace(/\./gi, "");
    let str ='';
    for (let i = nStr.length; i > 0; i -= 3){
        let a = ( (i-3) < 0 ) ? 0 : (i-3);
        str= nStr.slice(a,i) + '.' + str;
    }
    str= str.slice(0,str.length-1);
    return str;
}