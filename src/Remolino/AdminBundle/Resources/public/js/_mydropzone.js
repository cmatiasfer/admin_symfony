//Globals
var imageDefaultName = ''; // On gallery uploading
var newDefaultImageName = ''; // On gallery image deleteing
var deletedImage = ''; // Deleted imagen from gallery
var indexGalleryImage = 0;
var disableGalleryBtnActions = () => {
	$('.btn-insert-image, .item .actions').addClass('disabled');
}
var enableGalleryBtnActions = () => {
	$('.btn-insert-image, .item .actions').removeClass('disabled');
}
var filesUploaded = 0;

//version: 1.1v
//23/09
Dropzone.autoDiscover = false;
$(function () {
	currentId = $("#entity").attr("data-id");
	var section = $("#entity").attr("data-name");

	var settings = {};
	var addForm = $(".dropzoneGallery").attr('data-addForm');
	var rules_images = $(".dropzoneGallery").attr('data-rules');

	var fileType = $(".dropzoneGallery").attr('data-filetype');
	var lang = $(".dropzoneGallery").attr('data-lang');
	var maxfilesize = $(".dropzoneGallery").attr('data-maxfilesize');
	var modeEditionImage = $(".dropzoneGallery").attr('data-modeeditionimage');
	var folder = $("#entity").attr("data-folder");
	var folderGallery = $("#entity").attr("data-foldergallery");

	settings = {
		addForm: addForm,
		dropzoneStart: true,
		debug: true,
		entityGallery: folder,
		folder: folder,
		folderGallery: folderGallery,
		itemId: currentId,
		rules: JSON.parse(rules_images),
		inputOrder: false,
		maxFilesize: maxfilesize,
		modeEditionImage: modeEditionImage,
		langText: lang,
		typesFiles: fileType,
		section: section,
	};

	setTimeout(function () {
		var dz = createDropzone(settings);
	}, 500)

	/* BUTTONS LISTING ORDER  */
	//btn edit
	$(document).on('click', '.buttons-listing-order .edit', function () {
		activeSortable();
		$("#entity").find(".btn-success").not(".save-listing-order").attr("disabled", true);
	});

	//btn save
	$(document).on('click', '.save-listing-order', function () {
		$(".preload").fadeIn();
		var json = JSONlistingOrder()
		$.ajax({
			url: "/gallery/" + settings.entityGallery + "/update_listing_order",
			type: "POST",
			data: "listingOrder=" + json,
			dataType: "json",
			complete: function (data) {
				$(".preload").fadeOut();
				$("#entity").find(".btn-success").attr("disabled", false);
				if (settings.langText == "es") {
					toastr.success("Orden actualizado!");
				} else {
					toastr.success("Orden actualizado!");
				}

				disableSortable();
			}
		});
	});

	/* BUTTONS ACTIONS FOR IMAGES (3) / SHOW - OPEN FORM UPDATE - DELETE   */
	// (1)Button Show
	$(document).on("click", ".showItem", function () {
		var imageName = $(this).parent().parent().attr("data-name");
		var html = $(".preview").find("span[data-name='" + imageName + "']").attr("data-html");
		showImage.setContent(html);
		showImage.open();
		$(".tingle-modal--visible").find("img").on("load", function () {
			var modal = $(".tingle-modal--visible").find(".tingle-modal-box");
			if (modal.height() > $(window).height()) {
				setTimeout(function () {
					$("body").find(".tingle-modal--visible").addClass("tingle-modal--overflow");
				}, 500);
			}
		});
	});

	// (2)Button Update
	$(document).on("click", ".updateItem", function () {
		var section = $("#entity").attr("data-name");
		var id = $(this).parent().parent().attr("data-id");
		var fileName = $(this).parent().parent().attr("data-name");
		var type = fileName.split(".");

		var dataURL = "currentId=" + id;
		var html_landing = "";
		html_landing += "<div class='preload'>";
		html_landing += "	<div class='bubblingG'>";
		html_landing += "		<span id='bubblingG_1'></span>";
		html_landing += "		<span id='bubblingG_2'></span>";
		html_landing += "		<span id='bubblingG_3'></span>";
		html_landing += "	</div>";
		html_landing += "</div>";

		form_modal.setContent(html_landing);
		form_modal.open();
		$.ajax({
			url: "/gallery/" + settings.entityGallery + "/edit_form/" + id,
			type: "POST",
			data: dataURL,
			success: function (data) {
				form_modal.setContent(data);
				$("#" + settings.entityGallery + "_updateImage").parent().parent().css("display", "none");
				if ((section == "home" && type[1] == "jpeg") || type[1] == "png" || type[1] == "jpg") {
					$("#home_gallery").find("fieldset").hide();
				}
				resetJs(settings.entityGallery);
			},
			error: function () {
				form_modal.setContent("Error");
			}
		});
	});

	// (3)Button Delete
	$(document).on("click", ".trashItem", function () {
		$(".preload").fadeIn();
		deletedImage = $(this).parent().parent().data('name');
		disableGalleryBtnActions();
		$(this).parent().parent().parent().find("a").children(".btn").click();
	});

	/* MODAL OPEN - ACTION BTN SUBMIT */
	$(document).on("submit", "form[name='" + settings.folderGallery + "']", function (e) {
		e.preventDefault();
		Dropzone.autoDiscover = false;
		var form = $(this);
		var id = $(".tingle-modal-box").find("#seccion-ajax").attr("data-id");

		form.find("button.submit-ajax").text("Sending...");
		form.find("button.submit-ajax").attr("disabled", true);
		$.ajax({
			url: "/gallery/" + settings.entityGallery + "/edit_form/" + id,
			type: "POST",
			data: form.serialize(),
			success: function (data) {
				form.find("button.submit-ajax").text("update");
				form.find("button.submit-ajax").attr("disabled", false);
				if (data == "close") {
					if (settings.langText == "es") {
						toastr.success("Actualizado!");
					} else {
						toastr.success("Updated!");
					}
					form_modal.close();

					//Reset Dropzone
					$("#gallery").html("");
					$("#gallery").append(loading("html"));
					$("#gallery").append("<div class='dropzone'></div>");
					createDropzone(settings);
				} else {
					form_modal.setContent(data);

					resetJs(settings.entityGallery);
				}
			}
		});
	});

	toastr.options = {
		closeButton: false,
		debug: false,
		newestOnTop: false,
		progressBar: true,
		positionClass: "toast-bottom-right",
		preventDuplicates: false,
		onclick: null,
		showDuration: "300",
		hideDuration: "1000",
		timeOut: "5000",
		extendedTimeOut: "1000",
		showEasing: "swing",
		hideEasing: "linear",
		showMethod: "fadeIn",
		hideMethod: "fadeOut"
	};

	$(document).on('change', '#brands_gallery_effect', function () {
		console.log($(this).val());
		var currentValue = $(this).val();

		if (currentValue != "fade" && currentValue != "bounce" && currentValue != "zoom") {
			disableSelect("#brands_gallery_type");
			disableSelect("#brands_gallery_direction");
			disableSelect("#brands_gallery_duration");
		} else {
			enableSelect("#brands_gallery_type");
			enableSelect("#brands_gallery_direction");
			enableSelect("#brands_gallery_duration");
		}

	});

	//modal
	var showImage = new tingle.modal({
		footer: false,
		stickyFooter: false,
		closeMethods: ["overlay", "button", "escape"],
		closeLabel: "Close",
		cssClass: ["custom-class-1", "custom-class-2"],
		beforeClose: function () {
			// here's goes some logic
			// e.g. save content before closing the modal
			return true; // close the modal
			//return false; // nothing happens
		}
	});

	form_modal = new tingle.modal({
		footer: false,
		stickyFooter: false,
		closeMethods: ["overlay", "button", "escape"],
		closeLabel: "Close",
		cssClass: ["custom-class-1", "custom-class-2"],
		onClose: function () {
			$("body").removeClass("modal-open");
		},
		beforeClose: function () {
			// here's goes some logic
			// e.g. save content before closing the modal
			return true; // close the modal
			//return false; // nothing happens
		}
	});

	//CUSTOM SECTION
	// RedCreek - Dependiendo del valor de un select, muestro dropzone.
	if ($("#entity[data-name='custom_section']") && $("#entity[data-id]")) {
		$("#custom_section_template").change(function () {
			var value = $(this).val();
			if (value == "template3") {
				if (settings.langText == "es") {
					toastr.info("<a href='#gallery'>Ir a la Galeria.</a>");
				} else {
					toastr.info("<a href='#gallery'>Go To Gallery.</a>");
				}
				$("#gallery").show();
				$(".buttons-listing-order").show();
				createDropzone(settings);
			} else {
				$("#gallery").hide();
				$(".buttons-listing-order").hide();

				$("#gallery").html("");
				$("#gallery").append("<div class='dropzone'></div>");
			}
		});
	}
});

function loading() {
	var html = "";

	html += "<div class='preload'>";
	html += "	<div class='bubblingG'>";
	html += "		<span id='bubblingG_1'></span>";
	html += "		<span id='bubblingG_2'></span>";
	html += "		<span id='bubblingG_3'></span>";
	html += "	</div>";
	html += "</div>";


	return html;
}

function createDropzone(settings) {
	if (settings.debug) {
		console.log(settings);
	}

	$("#gallery").prepend(createDescription(settings));

	var dropzone = $(".dropzone").dropzone({
		maxFiles: 5000,
		maxFilesize: settings.maxFilesize,
		url: "/gallery/" + settings.entityGallery + "/upload",
		params: {
			currentId: settings.itemId
		},
		addRemoveLinks: true,
		dictRemoveFile: "<div class='btn btn-danger'><i class='fa fa-trash'></i></div>",
		acceptedFiles: settings.typesFiles,
		init: function () {
			var dz = this;
			this.on("thumbnail", function (file) {
				htmlPreviewModal(settings.folderGallery, file.name);
				// Validacion
				if (file.accepted || file.status == "added") {
					var result_valid = validTypeImage(file.width, file.height, settings);

					if (result_valid[0] && result_valid[1]) {
						file.acceptDimensions();
					} else {
						file.rejectDimensions();
					}

					filesUploaded++;
					disableGalleryBtnActions();
				}
			});

			var dataURL = "currentId=" + settings.itemId;

			$.ajax({
				url: "/gallery/" + settings.entityGallery + "/show",
				type: "POST",
				data: dataURL,
				dataType: "json",
				beforeSend: function () {
					$(".dz-default.dz-message").html("");
				},
				success: function (data) {
					$(".dz-default.dz-message").html("display", "none");
					if (data) {
						dz.on("addedfile", function (file) {
							if (file.status != "added") {
								// CREO PREVIEWS DE IMAGENES EN EL SERVIDOR
								// Contador de images en memoria - Dropzone
								var dz_images = $(".dz-preview").length - 1;
								var get_id = data[1][dz_images].id;
								var get_order = data[1][dz_images].order;
								var form = createFormEdit(
									get_id,
									get_order,
									data[0][dz_images].name,
									settings.addForm,
									settings.inputOrder
								);
								file.previewElement.appendChild(form);
							}
						});
						$.each(data[0], function (index, value) {
							dz.emit("addedfile", value);
							var nombreImg = value.name;
							if (nombreImg != undefined) {
								var nombreImgSplit = nombreImg.split(".");
								if (nombreImgSplit[1] == "mp4" || nombreImgSplit[1] == "avi" || nombreImgSplit[1] == "mkv") {
									dz.emit("thumbnail", value, "/bundles/remolinoadmin/dist/img/dz-ico-video.png");
								} else {
									dz.emit("thumbnail", value, "/images/" + settings.folderGallery + "/" + nombreImg);
								}
								dz.emit("complete", value);
							} else {
								dz.emit("thumbnail", value, "/bundles/remolinoadmin/dist/img/dz-warning.png");
								dz.emit("complete", value);
							}
						});
					}
				},
				complete: function () {
					setTimeout(function () {
						$("#gallery").find(".preload").fadeOut();
					}, 1500);
					// Init gallery Index
					indexGalleryImage = $('.dz-preview').length;
				}
			});
		},
		accept: function (file, done) {
			//Si cumple con las condiciones del tipo de imagen y tamaño(kb)
			if (file.type == "video/mp4") {
				done();
			}
			file.acceptDimensions = done;
			file.rejectDimensions = function () {
				done("Invalid dimension.");
			};
		},
		removedfile: function (file) {
			var id = file._removeLink.nextSibling.attributes[1].value;
			var listingOrder = {};

			$(".item").not(".item[data-id='" + id + "']").each(function (e) {
				var id = $(this).attr("data-id");
				var orderNew = (e + 1) * 100;
				$(this).find(".order-image").val(orderNew);
				var orderVal = $(this).find(".order-image").val();
				listingOrder[e] = {
					id: id,
					order: orderVal
				};
			});

			var convertListingOrder = JSON.stringify(listingOrder);
			var dataURL = "currentId=" + id + "&listingOrder=" + convertListingOrder;

			$.ajax({
				url: "/gallery/" + settings.entityGallery + "/delete",
				type: "POST",
				data: dataURL,
				success: function (data) {
					indexGalleryImage--;
					file.previewElement.remove();
					if (data.status != "ERROR") {
						$(".preload").fadeOut();
						if (settings.langText == "es") {
							toastr.info("Elemento borrado!");
						} else {
							toastr.info("Elemento borrado!");
						}

						var $firstItem = null;
						var higherOrder = 10000000;
						$(".item").each(function (index, element) {
							if ($(this).attr("data-order") <= higherOrder) {
								higherOrder = $(this).attr("data-order");
								$firstItem = $(this);
							}
						});

						if ($firstItem) {
							newDefaultImageName = $firstItem.attr("data-name");
						}

						$(document).trigger('click', '.save-listing-order');
						enableGalleryBtnActions();
						$('.btn-insert-image img').each(function () {
							if ($(this).attr('src') == '/images/products_gallery/' + deletedImage) {
								if (newDefaultImageName == '') {
									$(this).replaceWith('<i class="fa fa-camera"></i>');
								} else {
									if ($('.item')[0]) {
										$(this).attr('src', '/images/products_gallery/' + newDefaultImageName);
									} else {
										$(this).replaceWith('<i class="fa fa-camera"></i>');
										indexGalleryImage = 0;
									}
								}
							}

							if (!$('.item')[0]) {
								$(this).replaceWith('<i class="fa fa-camera"></i>');
								indexGalleryImage = 0;
							}
						});

						/* generateVariants(); */
					} else {
						if (settings.langText == "es") {
							toastr.error("Hubo un problema.En el Servidor!");
						} else {
							toastr.error("Error!");
						}
					}
				},
				error: function () {
					if (settings.langText == "es") {
						toastr.error("Hubo un problema.En el Servidor! - Ajax");
					} else {
						toastr.error("Error!");
					}
				}
			});
			$(".dz-message").css("display", "none");
		},
		success: function (file, response) {
			var dz = this;
			var dataURL = "currentId=" + settings.itemId;
			$.ajax({
				url: "/gallery/" + settings.entityGallery + "/show",
				type: "POST",
				data: dataURL,
				dataType: "json",
				success: function (data) {
					$('.btn-insert-image, .item .actions').removeClass('disabled');
					enableGalleryBtnActions();

					// Añade input text y btn a div element
					var getId = "";
					var getOrder = "";
					var form = createFormEdit(
						getId,
						getOrder,
						data[0][indexGalleryImage].name,
						settings.addForm,
						settings.inputOrder
					);
					file.previewElement.append(form);
					file.previewElement.children[4].style.opacity = 1;

					// ADD PREVIEW
					var fileName = data[0][indexGalleryImage].name;

					htmlPreviewModal(settings.folderGallery, fileName);

					$(".item").each(function (e) {
						$(this).attr("data-id", data[1][e].id);
						$(this).attr("data-order", data[1][e].order);
						$(this).find(".order-image").val(data[1][e].order);
					});

					imageDefaultName = data[0][0].name;
					if ($('.fa.fa-camera')[0]) {
						$('.fa.fa-camera').each(function () {
							$(this).replaceWith("<img src='/images/products_gallery/" + imageDefaultName + "' style='width:40px;' ></img>");
						});
					}

					indexGalleryImage++;
				}
			});

			// Esto detecta cuando la cola de archivos subidos a la galería esta en 0
			// En ese caso estarían todas las imágenes cargadas
			// Lo idel es recargar y ordenar la galería en este momento
			/* if (this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) { */
			/* generateVariants(); */
			/* $(".preload").fadeIn();
			setTimeout(function () {
				//Reset Dropzone
				$("#gallery").html("");
				$("#gallery").append(loading());
				$("#gallery").append("<div class='dropzone'></div>");
				createDropzone(settings);
			}, 1500);
		} */
		},
		sending: function (file, xhr, formData) {
			var total = $(".item").length;
			var typeImg;

			$(".item").each(function (e) {
				if (e == total - 1) {
					$(this).find(".order-image").val();
				}
			});

			/* if (file.width == file.height) {
				typeImg = "cuadrado";
			} else {
				if (file.width > file.height) {
					typeImg = "horizontal";
				} else {
					typeImg = "vertical";
				}
			} */
			/* var typeImg = "full-width";
			formData.append("type", typeImg); */
		}
	});

	return dropzone;
}

function createFormEdit(id, order, fileName, addForm, inputOrder) {
	//Creo un elemento padre(div) y dos hijos(inputText y contenedor de buttonUpdate)

	var divElement = document.createElement("div");
	var inputElement = document.createElement("input");
	var BtnViewImageElement = document.createElement("div");
	var BtnUpdateElement;
	if (addForm) {
		BtnUpdateElement = document.createElement("div");
	}
	var BtnDeleteElement = document.createElement("div");
	var actions = document.createElement("div");
	//Propiedades div padre
	divElement.className = "item";
	divElement.setAttribute("data-id", id);
	divElement.setAttribute("data-order", order);
	divElement.setAttribute("data-name", fileName);
	//Propiedades btnViewImage
	BtnViewImageElement.innerHTML =
		"<div class='btn bg-orange'><i class='fa fa-eye'></i></div>";
	BtnViewImageElement.className = "showItem";
	//Propiedades btnUpdate
	if (addForm) {
		// BtnUpdateElement.innerHTML = "<div class='btn bg-olive disabled'><i class='fa fa-pencil '></i></div>";
		BtnUpdateElement.innerHTML =
			"<div class='btn bg-olive'><i class='fa fa-pencil '></i></div>";
		BtnUpdateElement.className = "updateItem";
	}
	//Propiedades btnDelete
	BtnDeleteElement.innerHTML =
		"<div class='btn btn-danger'><i class='fa fa-trash'></i></div>";
	BtnDeleteElement.className = "trashItem";
	//Propiedades Input Text
	inputElement.type = "text";
	inputElement.onkeypress = validarNumericos;
	inputElement.className = "order-image form-group";
	inputElement.value = order;

	if (!inputOrder) {
		inputElement.style.display = "none";
	}
	actions.className = "actions";
	actions.appendChild(BtnViewImageElement);
	if (addForm) {
		actions.appendChild(BtnUpdateElement);
	}
	actions.appendChild(BtnDeleteElement);
	//Añade input text y btn a div element
	divElement.appendChild(inputElement);
	divElement.appendChild(actions);
	return divElement;
}

function validarNumericos(event) {
	if (event.charCode >= 48 && event.charCode <= 57) {
		return true;
	}
	return false;
}

function activeSortable() {
	$(".buttons-listing-order .edit").toggle();
	$(".buttons-listing-order .save-listing-order").toggle();
	$(".dropzone").addClass("mode-edit-order");
	animateCSS(".dropzone", "bounce");
	//var zone = $(".dropzone");
	var zone = document.querySelector(".dropzone");
	sortable = Sortable.create(zone, {
		animation: 150,
		easing: "cubic-bezier(1, 0, 0, 1)",
		ghostClass: "sortable-ghost", // Class name for the drop placeholder
		chosenClass: "sortable-chosen", // Class name for the chosen item
		dragClass: "sortable-drag", // Class name for the dragging item
		onStart: function () {
			$(".dropzone").addClass("drag");
		},
		onUpdate: function () {
			$(".dropzone").removeClass("drag");
			$(".item").each(function (e) {
				$(this)
					.find(".order-image")
					.val(e + 1 + "00");
			});
		}
	});
}

function disableSortable() {
	$(".buttons-listing-order .edit").toggle();
	$(".buttons-listing-order .save-listing-order").toggle();
	$(".dropzone").removeClass("mode-edit-order");
	animateCSS(".dropzone", "bounce");
	sortable.destroy();
}

function animateCSS(element, animationName, callback) {
	var node = document.querySelector(element);
	node.classList.add("animated", animationName);

	function handleAnimationEnd() {
		node.classList.remove("animated", animationName);
		node.removeEventListener("animationend", handleAnimationEnd);

		if (typeof callback === "function") callback();
	}

	node.addEventListener("animationend", handleAnimationEnd);
}

function JSONlistingOrder() {
	var listingOrder = {};
	$(".item").each(function (e) {
		var id = $(this).attr("data-id");
		var orderVal = $(this).find(".order-image").val();
		listingOrder[e] = {
			id: id,
			order: orderVal
		};
	});
	var convertListingOrder = JSON.stringify(listingOrder);
	return convertListingOrder;
}

function resetJs(modal) {
	// Cropper
	if ($(".cropper")[0]) {
		$(".cropper").each(function () {
			new Cropper($(this));
		});
	}

	$.fn.cropper.setDefaults({
		aspectRatio: 1.7777777777,
		zoomable: false,
		cropBoxResizable: false,
		movable: false,
		dragCrop: false,
		dragMode: "none"
	});

	//generateCKEditor()

	/* if ($("#" + modal + "_text_EN")[0] || $("#" + modal + "_text")[0]) {
		generateCKEditor("#" + modal + "_text");
	}
	if ($("#" + modal + "_title")[0]) {
		generateCKEditor("#" + modal + "_title");
	} */
}

function htmlPreviewModal(folder, name) {
	var html = "";
	if (name != undefined) {
		var fileFormat = name.split(".");
		if (fileFormat[1] == "mp4") {
			html = "<video playsinline autoplay loop muted style='width:100%;' data-name='" + name + "' controls><source src='/images/" + folder +
				"/" + name + "' type='video/mp4'> </video>";
		} else {
			html = "<img src='/images/" + folder + "/" + name + "' data-name='" + name + "' class='img-responsive preview-complete'>";
		}
	} else {
		html = "<img data-name='null' src='/bundles/remolinoadmin/dist/img/dz-image-error.png' data-name='error' style='width:400px;'> ";
	}
	$(".preview").append(
		'<span data-name="' + name + '" data-html="' + html + '"></span>'
	);
}

function generateCKEditor(textarea, type) {
	var toolbarOptions;

	switch (type) {
		case "text":
			toolbarOptions = ["heading", "|", "bold", "italic", "link"];
			break;
	}

	if (type) {
		ClassicEditor.create(document.querySelector(textarea), {
			toolbar: toolbarOptions
		}).then(editor => {
			window.editor = editor;
		}).catch(err => {
			console.error(err.stack);
		});
	} else {
		ClassicEditor.create(document.querySelector(textarea), {
			// toolbar: toolbarOptions
		}).then(editor => {
			window.editor = editor;
		}).catch(err => {
			console.error(err.stack);
		});
	}
}

function validTypeImage(fileW, fileH, settings) {
	var rules = settings.rules;
	var validImgMin = false;
	var validImgMax = false;

	if ('type' in rules.dimensions) {
		var currentImageType = "";
		if (fileW < fileH) {
			currentImageType = "vertical";
		}
		if (fileW == fileH) {
			currentImageType = "box";
		}
		if (fileW > fileH) {
			currentImageType = "horizontal";
		}

		const listRulesByType = rules.dimensions.type;
		var ruleByType = listRulesByType[currentImageType];

		var rulesWidth = ruleByType.width == undefined ? 0 : ruleByType.width;
		var rulesHeight = ruleByType.height == undefined ? 0 : ruleByType.height;
		var rulesMaxWidth = rulesWidth + 20;
		var rulesMaxHeight = rulesWidth + 20;
		console.log(fileW);
		console.log(fileH);

		/* 
		//Rangos
		if ((fileW >= rulesWidth && fileW <= rulesMaxWidth) && (fileH >= rulesHeight && fileH <= rulesMaxHeight)) { 
		*/
		if ((fileW >= rulesWidth) && (fileH >= rulesHeight)) {
			validImgMin = true;
			validImgMax = true;
		}
	} else {
		/* Modo máximo libre */
		var rulesHeight = rules.dimensions.height == undefined ? 0 : rules.dimensions.height;
		if (fileW >= rules.dimensions.width && fileH >= rulesHeight) {
			validImgMin = true;
			validImgMax = true;
		}
	}






	/* 
	var settingHeight = settings.ImageHeight;
	var settingWidth = settings.ImageWidth;
	var mode = "onlyHorizontal";
	
	if (settingWidth < settingHeight) {
		mode = "onlyVertical";
	}

	if (fileW >= fileH) {
		// Horizontal
		if (mode == "onlyHorizontal") {
		}

	} else {
		// Vertical
		if (mode == "onlyVertical") {
			if (fileW >= settings.ImageWidth && fileH >= settings.ImageHeight) {
				validImgMin = true;
				validImgMax = true;
			}
		}
	}
	*/
	return [validImgMin, validImgMax];
}

function createDescription(settings) {
	/* 
	var text_dim;
	if (settings.doubleTypeImage) {
		if (settings.langText == "es") {
			text_dim = "Imagen Horizontal: " + settings.minImageWidthVertical + "x * px";
			text_dim += "/ Imagen Vertical: " + settings.minImageHeightVertical + "x * px";
		} else {
			text_dim = "Image Horizontal: " + settings.minImageWidthVertical + "x * px";
			text_dim += "/ Image Vertical: " + settings.minImageHeightVertical + "x * px";
		}
	} else {
		text_dim = settings.maxImageWidth + " x * px";
	} 
	*/
	var rules = settings.rules;
	var textGallery;
	if (settings.langText == "es") {
		/* var ratio = "16:9";
		if (rules.dimensions.width < rules.dimensions.height) {
			var ratio = "9:16";
		} */
		var rulesHeigth = rules.dimensions.height == undefined ? 0 : rules.dimensions.height;
		textGallery = "<label> Arrastre los archivos dentro del area delimitada.";
		/* textGallery += "<br> Ratio: " + ratio; */
		textGallery += "<br> Dimensiones mínimas:";
		if ('type' in rules.dimensions) {
			textGallery += "<br>";
			for (type in rules.dimensions.type) {

				var text_type = "Formato ";
				if (type == "box") {
					text_type += "Cuadrado";
				}
				if (type == "horizontal") {
					text_type += "Rectangular";
				}
				if (type == "vertical") {
					text_type += "Vertical";
				}
				textGallery += text_type + ": " + rules.dimensions.type[type]["width"];
				if ('height' in rules.dimensions.type[type]) {
					textGallery += "x" + rules.dimensions.type[type]["height"];
				}
				textGallery += "px <br>";

			}
		} else {
			textGallery += rules.dimensions.width + " px ";
		}


		if (rulesHeigth > 0) {
			textGallery += "<br> Alto mínimo imágenes: " + rulesHeigth + " px ";
		}
		textGallery += "<br> Tipos de archivos permitidos: " + settings.typesFiles;
		textGallery += "<br> Peso máximo permitido por archivo: " + settings.maxFilesize + "MB </label>";
	}
	/*  else {
		// Convert text es to en
		textGallery = "<label> Gallery (Width minimum:" + text_dim + ") ";
		textGallery += "<br> Drop files here or click to upload.";
		textGallery += "<br> Types of files allowed:" + settings.typesFiles;
		textGallery += "<br> Maximum weight allowed per file: " + settings.maxFilesize + "MB </label>";
	} */
	return textGallery;
}

function disableSelect(select) {
	$(select + "option:first").attr('selected', true);
	$(select).val('');
	$(select).attr('disabled', 'disabled');
}
function enableSelect(select) {
	$(select + "option:first").attr('selected', true);
	$(select).val('');
	$(select).removeAttr('disabled');
}