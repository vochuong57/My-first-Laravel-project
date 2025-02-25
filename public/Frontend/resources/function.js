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
                el: '.swiper-pagination',
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

	HT.categorySwipperSlide = () => {
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

	HT.selectProductVariant = () => {
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
				HT.handleAttribute()
			})
		}
	}

	HT.handleAttribute = () => {
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
		console.log(count)
		// console.log($('.attribute .attribute-item').length)

		if(attribute_id.length < count){
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
						HT.setupVariantGallery(originalAlbum)
					}
				}
			})
		}
	}

	HT.setupVariantGallery = (album) => {
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

		HT.selectProductVariant()
		HT.handleAttribute()

		// HT.openPreviewVideo()
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