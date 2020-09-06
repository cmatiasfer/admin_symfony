//version: 1v
//31/05
Dropzone.autoDiscover = false;
$(function () {
	var section = $("#entity").attr("data-name");
	// var GALLERY = $("#entity").attr("data-gallery");
	var currentId = $("#entity").attr("data-id");
	var folder = $("#entity").attr("data-folder");
	var settings = {};

	switch (section) {
		case "home":
			settings = {
				dropzoneStart: true,
				section: section,
				itemId: currentId,
				folder: folder,
				maxImageWidth: 1400,
				maxImageHeight: 1200,
				maxFilesize: 10,
				typesFiles: ".jpg,.jpeg",
				langText: "es",
				openForm: true,
				entityGallery: folder,
				inputOrder: false,
				debug: false
			};
			break;
		case "studio":
			settings = {
				dropzoneStart: true,
				section: section,
				itemId: currentId,
				folder: folder,
				maxImageWidth: 1280,
				maxImageHeight: 720,
				maxFilesize: 10,
				typesFiles: ".jpg,.jpeg",
				langText: "es",
				openForm: true,
				entityGallery: folder,
				inputOrder: false,
				debug: false
			};
			break;
		case "project":
			settings = {
				dropzoneStart: true,
				section: section,
				itemId: currentId,
				folder: folder,
				maxImageWidth: 1280,
				maxImageHeight: 720,
				maxFilesize: 10,
				typesFiles: ".jpg,.jpeg",
				langText: "es",
				openForm: true,
				entityGallery: folder,
				inputOrder: false,
				debug: false
			};
			break;
	}

	if (settings.dropzoneStart == true) {
		var dz = createDropzone(settings);
	} else {
		$("#gallery").hide();
		$(".buttons-listing-order").hide();
	}

	//buttons listing order
	$(".buttons-listing-order .edit").click(function () {
		activeSortable();
		$("#entity").find(".btn-success").not(".save-listing-order").attr("disabled", true);
	});

	$(".save-listing-order").click(function (e) {
		$(".preload").fadeIn();
		var json = JSONlistingOrder();
		$.ajax({
			url: "/gallery/" + settings.entityGallery + "/update_listing_order",
			type: "POST",
			data: "listingOrder=" + json,
			dataType: "json",
			complete: function (data) {
				$(".preload").fadeOut();
				$("#entity").find(".btn-success").attr("disabled", false);
				if (settings.langText == "es") {
					toastr.success("Orden Actualizado!");
				} else {
					toastr.success("Listing Order Updated!");
				}
				disableSortable();
			}
		});
	});
	//Buttons Items
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

	$(document).on("click", ".updateItem", function () {
		var section = $("#entity").attr("data-name");
		var id = $(this).parent().parent().attr("data-id");
		var fileName = $(this).parent().parent().attr("data-name");
		var type = fileName.split(".");

		var dataURL = "currentId=" + id;
		var html = '<div class="preload"><div class="bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div></div>';
		form_modal.setContent(html);
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

	$(document).on("click", ".trashItem", function () {
		$(".preload").fadeIn();
		$(this).parent().parent().parent().find("a").children(".btn").click();
	});

	$(document).on("submit", "form[name='" + settings.entityGallery + "']", function (e) {
		e.preventDefault(e);
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
	//$("#entity[data-editcurrentid]") : mode edit

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

function createDropzone(settings) {
	if (settings.debug) {
		console.log(settings);
	}

	//Text Gallery
	var text_dim;
	if (settings.doubleTypeImage) {
		if (settings.langText == "es") {
			text_dim = "Imagen Horizontal: " + settings.maxImageWidth + "x * px";
			text_dim += "/ Imagen Vertical: " + settings.maxImageWidthVertical + "x * px";
		} else {
			text_dim = "Image Horizontal: " + settings.maxImageWidth + "x * px";
			text_dim += "/ Image Vertical: " + settings.maxImageWidthVertical + "x * px";
		}
	} else {
		text_dim = settings.maxImageWidth + " x * px";
	}
	var textGallery;
	if (settings.langText == "es") {
		textGallery = "<label> Arrastre los archivos dentro del area delimitada.";
		textGallery += "<br> Ancho mínimo imágenes horizontal: " + settings.maxImageWidth + " px ";
		if (settings.maxImageWidthVertical) {
			textGallery += "<br> Ancho mínimo imágenes Vertical: " + settings.maxImageWidthVertical + " px";
		}
		textGallery += "<br> Tipos de archivos permitidos: " + settings.typesFiles;
		textGallery += "<br> Peso máximo permitido por archivo: " + settings.maxFilesize + "MB </label>";
	} else {
		textGallery = "<label> Gallery (Width minimum:" + text_dim + ") ";
		textGallery += "<br> Drop files here or click to upload.";
		textGallery += "<br> Types of files allowed:" + settings.typesFiles;
		textGallery += "<br> Maximum weight allowed per file: " + settings.maxFilesize + "MB </label>";
	}

	$("#gallery").prepend(textGallery);

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
				htmlPreviewModal(settings.folder, file.name);

				// Validacion
				if (file.accepted || file.status == "added") {
					var result_valid = validTypeImage(file.width, file.height, settings);

					if (result_valid[0] && result_valid[1]) {
						file.acceptDimensions();
					} else {
						file.rejectDimensions();
					}
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
								//CREO PREVIEWS DE IMAGENES EN EL SERVIDOR
								//Contador de images en memoria - Dropzone
								var dz_images = $(".dz-preview").length - 1;

								var get_id = data[1][dz_images].id;
								var get_order = data[1][dz_images].order;

								var form = createFormEdit(
									get_id,
									get_order,
									data[0][dz_images].name,
									settings.openForm,
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
									dz.emit("thumbnail", value, "/images/" + settings.folder + "/" + nombreImg);
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
					$("#gallery").find(".preload").fadeOut("slow");
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
					file.previewElement.remove();
					if (data.status != "ERROR") {
						$(".preload").fadeOut();
						if (settings.langText == "es") {
							toastr.info("Item Eliminado!");
						} else {
							toastr.info("Item Deleted!");
						}
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
					var item_final = 0;
					var key_item_final = 0;
					var count = 0;
					//Capturo el ultimo registro
					$.each(data[1], function (index, value) {
						if (item_final <= value.id) {
							key_item_final = index;
							item_final = value.id;
						}
					});
					//Añade input text y btn a div element
					var getId = "";
					var getOrder = "";
					var form = createFormEdit(
						getId,
						getOrder,
						data[0][key_item_final].name,
						settings.openForm,
						settings.inputOrder
					);
					file.previewElement.append(form);
					file.previewElement.children[4].style.opacity = 1;

					//ADD PREVIEW
					var fileName = data[0][key_item_final].name;

					htmlPreviewModal(settings.folder, fileName);

					$(".item").each(function (e) {
						$(this).attr("data-id", data[1][e].id);
						$(this).attr("data-order", data[1][e].order);
						var fileName = data[0][e].name;
						$(this)
							.find(".order-image")
							.val(data[1][e].order);
					});
				}
			});
		},
		sending: function (file, xhr, formData) {
			var total = $(".item").length;
			var typeImg;

			$(".item").each(function (e) {
				if (e == total - 1) {
					$(this).find(".order-image").val();
				}
			});

			if (settings.doubleTypeImage) {
				if (file.width > file.height) {
					typeImg = "horizontal";
				} else {
					typeImg = "vertical";
				}
				formData.append("type", typeImg);
			}
		}
	});
	return dropzone;
}

function createFormEdit(id, order, fileName, openForm, inputOrder) {
	//Creo un elemento padre(div) y dos hijos(inputText y contenedor de buttonUpdate)

	var divElement = document.createElement("div");
	var inputElement = document.createElement("input");
	var BtnViewImageElement = document.createElement("div");
	var BtnUpdateElement;
	if (openForm) {
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
	if (openForm) {
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
	if (openForm) {
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

	if ($("#" + modal + "_text_EN")[0] || $("#" + modal + "_text")[0]) {
		generateCKEditor("#" + modal + "_text");
	}
	if ($("#" + modal + "_title")[0]) {
		generateCKEditor("#" + modal + "_title");
	}
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
	var validImgMin = false;
	var validImgMax = false;

	if (fileW >= fileH) {
		if (fileW >= settings.maxImageWidth) {
			validImgMin = true;
			validImgMax = true;
		}
	} else {
		if (settings.doubleTypeImage) {
			if (fileW >= settings.maxImageWidthVertical) {
				validImgMin = true;
				validImgMax = true;
			}
		} else {
			validImgMin = false;
			validImgMax = false;
		}
	}
	console.log(validImgMin, validImgMax);
	return [validImgMin, validImgMax];
}