window.CMB2 = window.CMB2 || {}, function (window, document, $, cmb, undefined) {
	"use strict";
	var $document, l10n = window.cmb2_l10, setTimeout = window.setTimeout, $id = function (selector) {
		return $(document.getElementById(selector))
	}, defaults = {
		idNumber: !1,
		repeatEls: 'input:not([type="button"],[id^=filelist]),select,textarea,.cmb2-media-status',
		noEmpty: 'input:not([type="button"]):not([type="radio"]):not([type="checkbox"]),textarea',
		repeatUpdate: 'input:not([type="button"]),select,textarea,label',
		styleBreakPoint: 450,
		mediaHandlers: {},
		defaults: {
			time_picker: l10n.defaults.time_picker,
			date_picker: l10n.defaults.date_picker,
			color_picker: l10n.defaults.color_picker || {},
			code_editor: l10n.defaults.code_editor
		},
		media: {frames: {}}
	};
	cmb.metabox = function () {
		return cmb.$metabox ? cmb.$metabox : (cmb.$metabox = $(".cmb2-wrap > .cmb2-metabox"), cmb.$metabox)
	}, cmb.init = function () {
		$document = $(document), $.extend(cmb, defaults), cmb.trigger("cmb_pre_init");
		var $metabox = cmb.metabox(), $repeatGroup = $metabox.find(".cmb-repeatable-group");
		cmb.initPickers($metabox.find('input[type="text"].cmb2-timepicker'), $metabox.find('input[type="text"].cmb2-datepicker'), $metabox.find('input[type="text"].cmb2-colorpicker')), cmb.initCodeEditors($metabox.find(".cmb2-textarea-code:not(.disable-codemirror)")), $('<p><span class="button-secondary cmb-multicheck-toggle">' + l10n.strings.check_toggle + "</span></p>").insertBefore(".cmb2-checkbox-list:not(.no-select-all)"), cmb.makeListSortable(), cmb.makeRepeatableSortable(), $metabox.on("change", ".cmb2_upload_file", function () {
			cmb.media.field = $(this).attr("id"), $id(cmb.media.field + "_id").val("")
		}).on("click", ".cmb-multicheck-toggle", cmb.toggleCheckBoxes).on("click", ".cmb2-upload-button", cmb.handleMedia).on("click", ".cmb-attach-list li, .cmb2-media-status .img-status img, .cmb2-media-status .file-status > span", cmb.handleFileClick).on("click", ".cmb2-remove-file-button", cmb.handleRemoveMedia).on("click", ".cmb-add-group-row", cmb.addGroupRow).on("click", ".cmb-add-row-button", cmb.addAjaxRow).on("click", ".cmb-remove-group-row", cmb.removeGroupRow).on("click", ".cmb-remove-row-button", cmb.removeAjaxRow).on("keyup paste focusout", ".cmb2-oembed", cmb.maybeOembed).on("cmb2_remove_row", ".cmb-repeatable-group", cmb.resetTitlesAndIterator).on("click", ".cmbhandle, .cmbhandle + .cmbhandle-title", cmb.toggleHandle), $repeatGroup.length && $repeatGroup.on("cmb2_add_row", cmb.emptyValue).on("cmb2_add_row", cmb.setDefaults).filter(".sortable").each(function () {
			$(this).find(".cmb-remove-group-row-button").before('<a class="button-secondary cmb-shift-rows move-up alignleft" href="#"><span class="' + l10n.up_arrow_class + '"></span></a> <a class="button-secondary cmb-shift-rows move-down alignleft" href="#"><span class="' + l10n.down_arrow_class + '"></span></a>')
		}).on("click", ".cmb-shift-rows", cmb.shiftRows), setTimeout(cmb.resizeoEmbeds, 500), $(window).on("resize", cmb.resizeoEmbeds), $id("addtag").length && cmb.listenTagAdd(), cmb.trigger("cmb_init")
	}, cmb.listenTagAdd = function () {
		$document.ajaxSuccess(function (evt, xhr, settings) {
			settings.data && settings.data.length && -1 !== settings.data.indexOf("action=add-tag") && cmb.resetBoxes($id("addtag").find(".cmb2-wrap > .cmb2-metabox"))
		})
	}, cmb.resetBoxes = function ($boxes) {
		$.each($boxes, function () {
			cmb.resetBox($(this))
		})
	}, cmb.resetBox = function ($box) {
		$box.find(".wp-picker-clear").trigger("click"), $box.find(".cmb2-remove-file-button").trigger("click"), $box.find(".cmb-row.cmb-repeatable-grouping:not(:first-of-type) .cmb-remove-group-row").click(), $box.find(".cmb-repeat-row:not(:first-child)").remove(), $box.find('input:not([type="button"]),select,textarea').each(function () {
			var $element = $(this), tagName = $element.prop("tagName");
			if ("INPUT" === tagName) {
				var elType = $element.attr("type");
				"checkbox" === elType || "radio" === elType ? $element.prop("checked", !1) : $element.val("")
			}
			"SELECT" === tagName && $("option:selected", this).prop("selected", !1), "TEXTAREA" === tagName && $element.html("")
		})
	}, cmb.resetTitlesAndIterator = function (evt) {
		evt.group && $(".cmb-repeatable-group.repeatable").each(function () {
			var $table = $(this), groupTitle = $table.find(".cmb-add-group-row").data("grouptitle");
			$table.find(".cmb-repeatable-grouping").each(function (rowindex) {
				var $row = $(this), $rowTitle = $row.find("h3.cmb-group-title");
				$row.data("iterator", rowindex), $rowTitle.length && $rowTitle.text(groupTitle.replace("{#}", rowindex + 1))
			})
		})
	}, cmb.toggleHandle = function (evt) {
		evt.preventDefault(), cmb.trigger("postbox-toggled", $(this).parent(".postbox").toggleClass("closed"))
	}, cmb.toggleCheckBoxes = function (evt) {
		evt.preventDefault();
		var $this = $(this), $multicheck = $this.closest(".cmb-td").find("input[type=checkbox]:not([disabled])");
		$this.data("checked") ? ($multicheck.prop("checked", !1), $this.data("checked", !1)) : ($multicheck.prop("checked", !0), $this.data("checked", !0))
	}, cmb.handleMedia = function (evt) {
		evt.preventDefault();
		var $el = $(this);
		cmb.attach_id = !$el.hasClass("cmb2-upload-list") && $el.closest(".cmb-td").find(".cmb2-upload-file-id").val(), cmb.attach_id = "0" !== cmb.attach_id && cmb.attach_id, cmb._handleMedia($el.prev("input.cmb2-upload-file").attr("id"), $el.hasClass("cmb2-upload-list"))
	}, cmb.handleFileClick = function (evt) {
		if (!$(evt.target).is("a")) {
			evt.preventDefault();
			var $el = $(this), $td = $el.closest(".cmb-td"),
				isList = $td.find(".cmb2-upload-button").hasClass("cmb2-upload-list");
			cmb.attach_id = isList ? $el.find('input[type="hidden"]').data("id") : $td.find(".cmb2-upload-file-id").val(), cmb.attach_id && cmb._handleMedia($td.find("input.cmb2-upload-file").attr("id"), isList, cmb.attach_id)
		}
	}, cmb._handleMedia = function (id, isList) {
		if (wp) {
			var media, handlers;
			if (handlers = cmb.mediaHandlers, media = cmb.media, media.field = id, media.$field = $id(media.field), media.fieldData = media.$field.data(), media.previewSize = media.fieldData.previewsize, media.sizeName = media.fieldData.sizename, media.fieldName = media.$field.attr("name"), media.isList = isList, id in media.frames) return media.frames[id].open();
			media.frames[id] = wp.media({
				title: cmb.metabox().find('label[for="' + id + '"]').text(),
				library: media.fieldData.queryargs || {},
				button: {text: l10n.strings[isList ? "upload_files" : "upload_file"]},
				multiple: !!isList && "add"
			}), media.frames[id].states.first().set("filterable", "all"), cmb.trigger("cmb_media_modal_init", media), handlers.list = function (selection, returnIt) {
				var attachmentHtml, fileGroup = [];
				if (handlers.list.templates || (handlers.list.templates = {
						image: wp.template("cmb2-list-image"),
						file: wp.template("cmb2-list-file")
					}), selection.each(function (attachment) {
						attachmentHtml = handlers.getAttachmentHtml(attachment, "list"), fileGroup.push(attachmentHtml)
					}), returnIt) return fileGroup;
				media.$field.siblings(".cmb2-media-status").append(fileGroup)
			}, handlers.single = function (selection) {
				handlers.single.templates || (handlers.single.templates = {
					image: wp.template("cmb2-single-image"),
					file: wp.template("cmb2-single-file")
				});
				var attachment = selection.first();
				media.$field.val(attachment.get("url")), $id(media.field + "_id").val(attachment.get("id"));
				var attachmentHtml = handlers.getAttachmentHtml(attachment, "single");
				media.$field.siblings(".cmb2-media-status").slideDown().html(attachmentHtml)
			}, handlers.getAttachmentHtml = function (attachment, templatesId) {
				var isImage = "image" === attachment.get("type"), data = handlers.prepareData(attachment, isImage);
				return handlers[templatesId].templates[isImage ? "image" : "file"](data)
			}, handlers.prepareData = function (data, image) {
				return image && handlers.getImageData.call(data, 50), data = data.toJSON(), data.mediaField = media.field, data.mediaFieldName = media.fieldName, data.stringRemoveImage = l10n.strings.remove_image, data.stringFile = l10n.strings.file, data.stringDownload = l10n.strings.download, data.stringRemoveFile = l10n.strings.remove_file, data
			}, handlers.getImageData = function (fallbackSize) {
				var previewW = media.previewSize[0] || fallbackSize, previewH = media.previewSize[1] || fallbackSize,
					url = this.get("url"), width = this.get("width"), height = this.get("height"),
					sizes = this.get("sizes");
				return sizes && (sizes[media.sizeName] ? (url = sizes[media.sizeName].url, width = sizes[media.sizeName].width, height = sizes[media.sizeName].height) : sizes.large && (url = sizes.large.url, width = sizes.large.width, height = sizes.large.height)), width > previewW && (height = Math.floor(previewW * height / width), width = previewW), height > previewH && (width = Math.floor(previewH * width / height), height = previewH), width || (width = previewW), height || (height = "svg" === this.get("filename").split(".").pop() ? "100%" : previewH), this.set("sizeUrl", url), this.set("sizeWidth", width), this.set("sizeHeight", height), this
			}, handlers.selectFile = function () {
				var selection = media.frames[id].state().get("selection"), type = isList ? "list" : "single";
				cmb.attach_id && isList ? $('[data-id="' + cmb.attach_id + '"]').parents("li").replaceWith(handlers.list(selection, !0)) : handlers[type](selection), cmb.trigger("cmb_media_modal_select", selection, media)
			}, handlers.openModal = function () {
				var attach, selection = media.frames[id].state().get("selection");
				cmb.attach_id ? (attach = wp.media.attachment(cmb.attach_id), attach.fetch(), selection.set(attach ? [attach] : [])) : selection.reset(), cmb.trigger("cmb_media_modal_open", selection, media)
			}, media.frames[id].on("select", handlers.selectFile).on("open", handlers.openModal), media.frames[id].open()
		}
	}, cmb.handleRemoveMedia = function (evt) {
		evt.preventDefault();
		var $this = $(this);
		return $this.is(".cmb-attach-list .cmb2-remove-file-button") ? ($this.parents(".cmb2-media-item").remove(), !1) : (cmb.media.field = $this.attr("rel"), cmb.metabox().find(document.getElementById(cmb.media.field)).val(""), cmb.metabox().find(document.getElementById(cmb.media.field + "_id")).val(""), $this.parents(".cmb2-media-status").html(""), !1)
	}, cmb.cleanRow = function ($row, prevNum, group) {
		var $elements = $row.find(cmb.repeatUpdate);
		if (group) {
			var $other = $row.find("[id]").not(cmb.repeatUpdate);
			$row.find(".cmb-repeat-table .cmb-repeat-row:not(:first-child)").remove(), $other.length && $other.each(function () {
				var $_this = $(this), oldID = $_this.attr("id"),
					newID = oldID.replace("_" + prevNum, "_" + cmb.idNumber),
					$buttons = $row.find('[data-selector="' + oldID + '"]');
				$_this.attr("id", newID), $buttons.length && $buttons.attr("data-selector", newID).data("selector", newID)
			})
		}
		return $elements.filter(":checked").removeAttr("checked"), $elements.find(":checked").removeAttr("checked"), $elements.filter(":selected").removeAttr("selected"), $elements.find(":selected").removeAttr("selected", !1), $row.find("h3.cmb-group-title").length && $row.find("h3.cmb-group-title").text($row.data("title").replace("{#}", cmb.idNumber + 1)), $elements.each(function () {
			cmb.elReplacements($(this), prevNum, group)
		}), cmb
	}, cmb.elReplacements = function ($newInput, prevNum, group) {
		var newID, oldID, oldFor = $newInput.attr("for"), oldVal = $newInput.val(), type = $newInput.prop("type"),
			defVal = cmb.getFieldArg($newInput, "default"), newVal = void 0 !== defVal && !1 !== defVal ? defVal : "",
			tagName = $newInput.prop("tagName"), checkable = ("radio" === type || "checkbox" === type) && oldVal,
			attrs = {};
		if (oldFor) attrs = {for: oldFor.replace("_" + prevNum, "_" + cmb.idNumber)}; else {
			var newName, oldName = $newInput.attr("name");
			oldID = $newInput.attr("id"), group ? (newName = oldName ? oldName.replace("[" + prevNum + "][", "[" + cmb.idNumber + "][") : "", newID = oldID ? oldID.replace("_" + prevNum + "_", "_" + cmb.idNumber + "_") : "") : (newName = oldName ? cmb.replaceLast(oldName, "[" + prevNum + "]", "[" + cmb.idNumber + "]") : "", newID = oldID ? cmb.replaceLast(oldID, "_" + prevNum, "_" + cmb.idNumber) : ""), attrs = {
				id: newID,
				name: newName
			}
		}
		if ("TEXTAREA" === tagName && $newInput.html(newVal), "SELECT" === tagName && undefined !== typeof defVal) {
			var $toSelect = $newInput.find('[value="' + defVal + '"]');
			$toSelect.length && $toSelect.attr("selected", "selected").prop("selected", "selected")
		}
		return checkable && ($newInput.removeAttr("checked"), undefined !== typeof defVal && oldVal === defVal && $newInput.attr("checked", "checked").prop("checked", "checked")), !group && $newInput[0].hasAttribute("data-iterator") && (attrs["data-iterator"] = cmb.idNumber), $newInput.removeClass("hasDatepicker").val(checkable || newVal).attr(attrs), $newInput
	}, cmb.newRowHousekeeping = function ($row) {
		var $colorPicker = $row.find(".wp-picker-container"), $list = $row.find(".cmb2-media-status");
		return $colorPicker.length && $colorPicker.each(function () {
			var $td = $(this).parent();
			$td.html($td.find('input[type="text"].cmb2-colorpicker').attr("style", ""))
		}), $list.length && $list.empty(), cmb
	}, cmb.afterRowInsert = function ($row) {
		cmb.initPickers($row.find('input[type="text"].cmb2-timepicker'), $row.find('input[type="text"].cmb2-datepicker'), $row.find('input[type="text"].cmb2-colorpicker'))
	}, cmb.updateNameAttr = function () {
		var $this = $(this), name = $this.attr("name");
		if (void 0 !== name) {
			var prevNum = parseInt($this.parents(".cmb-repeatable-grouping").data("iterator"), 10),
				newNum = prevNum - 1, $newName = name.replace("[" + prevNum + "]", "[" + newNum + "]");
			$this.attr("name", $newName)
		}
	}, cmb.emptyValue = function (evt, row) {
		$(cmb.noEmpty, row).val("")
	}, cmb.setDefaults = function (evt, row) {
		$(cmb.noEmpty, row).each(function () {
			var $el = $(this), defVal = cmb.getFieldArg($el, "default");
			void 0 !== defVal && !1 !== defVal && $el.val(defVal)
		})
	}, cmb.addGroupRow = function (evt) {
		evt.preventDefault();
		var $this = $(this);
		cmb.triggerElement($this, "cmb2_add_group_row_start", $this);
		var $table = $id($this.data("selector")), $oldRow = $table.find(".cmb-repeatable-grouping").last(),
			prevNum = parseInt($oldRow.data("iterator"), 10);
		cmb.idNumber = parseInt(prevNum, 10) + 1;
		for (var $row = $oldRow.clone(); $table.find('.cmb-repeatable-grouping[data-iterator="' + cmb.idNumber + '"]').length > 0;) cmb.idNumber++;
		cmb.newRowHousekeeping($row.data("title", $this.data("grouptitle"))).cleanRow($row, prevNum, !0), $row.find(".cmb-add-row-button").prop("disabled", !1);
		var $newRow = $('<div class="postbox cmb-row cmb-repeatable-grouping" data-iterator="' + cmb.idNumber + '">' + $row.html() + "</div>");
		$oldRow.after($newRow), cmb.afterRowInsert($newRow), cmb.triggerElement($table, {
			type: "cmb2_add_row",
			group: !0
		}, $newRow)
	}, cmb.addAjaxRow = function (evt) {
		evt.preventDefault();
		var $this = $(this), $table = $id($this.data("selector")), $emptyrow = $table.find(".empty-row"),
			prevNum = parseInt($emptyrow.find("[data-iterator]").data("iterator"), 10);
		cmb.idNumber = parseInt(prevNum, 10) + 1;
		var $row = $emptyrow.clone();
		cmb.newRowHousekeeping($row).cleanRow($row, prevNum), $emptyrow.removeClass("empty-row hidden").addClass("cmb-repeat-row"), $emptyrow.after($row), cmb.afterRowInsert($row), cmb.triggerElement($table, {
			type: "cmb2_add_row",
			group: !1
		}, $row)
	}, cmb.removeGroupRow = function (evt) {
		evt.preventDefault();
		var $this = $(this), $table = $id($this.data("selector")), $parent = $this.parents(".cmb-repeatable-grouping");
		if ($table.find(".cmb-repeatable-grouping").length < 2) return cmb.resetRow($parent.parents(".cmb-repeatable-group").find(".cmb-add-group-row"), $this);
		cmb.triggerElement($table, "cmb2_remove_group_row_start", $this), $parent.nextAll(".cmb-repeatable-grouping").find(cmb.repeatEls).each(cmb.updateNameAttr), $parent.remove(), cmb.triggerElement($table, {
			type: "cmb2_remove_row",
			group: !0
		})
	}, cmb.removeAjaxRow = function (evt) {
		evt.preventDefault();
		var $this = $(this);
		if (!$this.hasClass("button-disabled")) {
			var $parent = $this.parents(".cmb-row"), $table = $this.parents(".cmb-repeat-table");
			if ($table.find(".cmb-row").length <= 2) return cmb.resetRow($parent.find(".cmb-add-row-button"), $this);
			$parent.hasClass("empty-row") && $parent.prev().addClass("empty-row").removeClass("cmb-repeat-row"), $this.parents(".cmb-repeat-table .cmb-row").remove(), cmb.triggerElement($table, {
				type: "cmb2_remove_row",
				group: !1
			})
		}
	}, cmb.resetRow = function ($addNewBtn, $removeBtn) {
		$addNewBtn.trigger("click"), $removeBtn.trigger("click")
	}, cmb.shiftRows = function (evt) {
		evt.preventDefault();
		var $this = $(this), $from = $this.parents(".cmb-repeatable-grouping"),
			$goto = $this.hasClass("move-up") ? $from.prev(".cmb-repeatable-grouping") : $from.next(".cmb-repeatable-grouping");
		if (cmb.triggerElement($this, "cmb2_shift_rows_enter", $this, $from, $goto), $goto.length) {
			cmb.triggerElement($this, "cmb2_shift_rows_start", $this, $from, $goto);
			var inputVals = [];
			$from.find(cmb.repeatEls).each(function () {
				var val, $element = $(this), elType = $element.attr("type");
				val = $element.hasClass("cmb2-media-status") ? $element.html() : "checkbox" === elType || "radio" === elType ? $element.is(":checked") : "select" === $element.prop("tagName") ? $element.is(":selected") : $element.val(), inputVals.push({
					val: val,
					$: $element
				})
			}), $goto.find(cmb.repeatEls).each(function (index) {
				var val, $element = $(this), elType = $element.attr("type");
				if ($element.hasClass("cmb2-media-status")) {
					var toRowId = $element.closest(".cmb-repeatable-grouping").attr("data-iterator"),
						fromRowId = inputVals[index].$.closest(".cmb-repeatable-grouping").attr("data-iterator");
					val = $element.html(), $element.html(inputVals[index].val), inputVals[index].$.html(val), inputVals[index].$.find("input").each(function () {
						var name = $(this).attr("name");
						name = name.replace("[" + toRowId + "]", "[" + fromRowId + "]"), $(this).attr("name", name)
					}), $element.find("input").each(function () {
						var name = $(this).attr("name");
						name = name.replace("[" + fromRowId + "]", "[" + toRowId + "]"), $(this).attr("name", name)
					})
				} else "checkbox" === elType ? (inputVals[index].$.prop("checked", $element.is(":checked")), $element.prop("checked", inputVals[index].val)) : "radio" === elType ? ($element.is(":checked") && inputVals[index].$.attr("data-checked", "true"), inputVals[index].$.is(":checked") && $element.attr("data-checked", "true")) : "select" === $element.prop("tagName") ? (inputVals[index].$.prop("selected", $element.is(":selected")), $element.prop("selected", inputVals[index].val)) : (inputVals[index].$.val($element.val()), $element.val(inputVals[index].val))
			}), $from.find("input[data-checked=true]").prop("checked", !0).removeAttr("data-checked"), $goto.find("input[data-checked=true]").prop("checked", !0).removeAttr("data-checked"), $from.find('input[type="text"].cmb2-colorpicker').trigger("change"), $goto.find('input[type="text"].cmb2-colorpicker').trigger("change"), cmb.triggerElement($this, "cmb2_shift_rows_complete", $this, $from, $goto)
		}
	}, cmb.initPickers = function ($timePickers, $datePickers, $colorPickers) {
		cmb.initDateTimePickers($timePickers, "timepicker", "time_picker"), cmb.initDateTimePickers($datePickers, "datepicker", "date_picker"), cmb.initColorPickers($colorPickers)
	}, cmb.initDateTimePickers = function ($selector, method, defaultKey) {
		$selector.length && $selector[method]("destroy").each(function () {
			var $this = $(this), fieldOpts = $this.data(method) || {},
				options = $.extend({}, cmb.defaults[defaultKey], fieldOpts);
			$this[method](cmb.datePickerSetupOpts(fieldOpts, options, method))
		})
	}, cmb.datePickerSetupOpts = function (fieldOpts, options, method) {
		var existing = $.extend({}, options);
		return options.beforeShow = function (input, inst) {
			"timepicker" === method && cmb.addTimePickerClasses(inst.dpDiv), $id("ui-datepicker-div").addClass("cmb2-element"), "function" == typeof existing.beforeShow && existing.beforeShow(input, inst)
		}, "timepicker" === method && (options.onChangeMonthYear = function (year, month, inst, picker) {
			cmb.addTimePickerClasses(inst.dpDiv), "function" == typeof existing.onChangeMonthYear && existing.onChangeMonthYear(year, month, inst, picker)
		}), options.onClose = function (dateText, inst) {
			var $picker = $id("ui-datepicker-div").removeClass("cmb2-element").hide();
			"timepicker" !== method || $(inst.input).val() || inst.input.val($picker.find(".ui_tpicker_time").text()), "function" == typeof existing.onClose && existing.onClose(dateText, inst)
		}, options
	}, cmb.addTimePickerClasses = function ($picker) {
		var func = cmb.addTimePickerClasses;
		func.count = func.count || 0, setTimeout(function () {
			$picker.find(".ui-priority-secondary").length ? ($picker.find(".ui-priority-secondary").addClass("button-secondary"), $picker.find(".ui-priority-primary").addClass("button-primary"), func.count = 0) : func.count < 5 && (func.count++, func($picker))
		}, 10)
	}, cmb.initColorPickers = function ($selector) {
		$selector.length && ("object" == typeof jQuery.wp && "function" == typeof jQuery.wp.wpColorPicker ? $selector.each(function () {
			var $this = $(this), fieldOpts = $this.data("colorpicker") || {};
			$this.wpColorPicker($.extend({}, cmb.defaults.color_picker, fieldOpts))
		}) : $selector.each(function (i) {
			$(this).after('<div id="picker-' + i + '" style="z-index: 1000; background: #EEE; border: 1px solid #CCC; position: absolute; display: block;"></div>'), $id("picker-" + i).hide().farbtastic($(this))
		}).focus(function () {
			$(this).next().show()
		}).blur(function () {
			$(this).next().hide()
		}))
	}, cmb.initCodeEditors = function ($selector) {
		cmb.defaults.code_editor && wp && wp.codeEditor && $selector.length && $selector.each(function () {
			wp.codeEditor.initialize(this.id, cmb.codeEditorArgs($(this).data("codeeditor")))
		})
	}, cmb.codeEditorArgs = function (overrides) {
		var props = ["codemirror", "csslint", "jshint", "htmlhint"], args = $.extend({}, cmb.defaults.code_editor);
		overrides = overrides || {};
		for (var i = props.length - 1; i >= 0; i--) overrides.hasOwnProperty(props[i]) && (args[props[i]] = $.extend({}, args[props[i]] || {}, overrides[props[i]]));
		return args
	}, cmb.makeListSortable = function () {
		var $filelist = cmb.metabox().find(".cmb2-media-status.cmb-attach-list");
		$filelist.length && $filelist.sortable({cursor: "move"}).disableSelection()
	}, cmb.makeRepeatableSortable = function () {
		var $repeatables = cmb.metabox().find(".cmb-repeat-table .cmb-field-list");
		$repeatables.length && $repeatables.sortable({items: ".cmb-repeat-row", cursor: "move"})
	}, cmb.maybeOembed = function (evt) {
		var $this = $(this);
		({
			focusout: function () {
				setTimeout(function () {
					cmb.spinner(".cmb2-metabox", !0)
				}, 2e3)
			}, keyup: function () {
				var betw = function (min, max) {
					return evt.which <= max && evt.which >= min
				};
				(betw(48, 90) || betw(96, 111) || betw(8, 9) || 187 === evt.which || 190 === evt.which) && cmb.doAjax($this, evt)
			}, paste: function () {
				setTimeout(function () {
					cmb.doAjax($this)
				}, 100)
			}
		})[evt.type]()
	}, cmb.resizeoEmbeds = function () {
		cmb.metabox().each(function () {
			var $this = $(this), $tableWrap = $this.parents(".inside"),
				isSide = $this.parents(".inner-sidebar").length || $this.parents("#side-sortables").length,
				isSmall = isSide, isSmallest = !1;
			if (!$tableWrap.length) return !0;
			var tableW = $tableWrap.width();
			cmb.styleBreakPoint > tableW && (isSmall = !0, isSmallest = cmb.styleBreakPoint - 62 > tableW), tableW = isSmall ? tableW : Math.round(.82 * $tableWrap.width() * .97);
			var newWidth = tableW - 30;
			if (!isSmall || isSide || isSmallest || (newWidth -= 75), newWidth > 639) return !0;
			var $embeds = $this.find(".cmb-type-oembed .embed-status"),
				$children = $embeds.children().not(".cmb2-remove-wrapper");
			if (!$children.length) return !0;
			$children.each(function () {
				var $this = $(this), iwidth = $this.width(), iheight = $this.height(), _newWidth = newWidth;
				$this.parents(".cmb-repeat-row").length && !isSmall && (_newWidth = newWidth - 91, _newWidth = 785 > tableW ? _newWidth - 15 : _newWidth);
				var newHeight = Math.round(_newWidth * iheight / iwidth);
				$this.width(_newWidth).height(newHeight)
			})
		})
	}, cmb.log = function () {
		l10n.script_debug && console && "function" == typeof console.log && console.log.apply(console, arguments)
	}, cmb.spinner = function ($context, hide) {
		var m = hide ? "removeClass" : "addClass";
		$(".cmb-spinner", $context)[m]("is-active")
	}, cmb.doAjax = function ($obj) {
		var oembed_url = $obj.val();
		if (!(oembed_url.length < 6)) {
			var field_id = $obj.attr("id"), $context = $obj.closest(".cmb-td"),
				$embed_container = $context.find(".embed-status"), $embed_wrap = $context.find(".embed_wrap"),
				$child_el = $embed_container.find(":first-child"),
				oembed_width = $embed_container.length && $child_el.length ? $child_el.width() : $obj.width();
			cmb.log("oembed_url", oembed_url, field_id), cmb.spinner($context), $embed_wrap.html(""), setTimeout(function () {
				$(".cmb2-oembed:focus").val() === oembed_url && $.ajax({
					type: "post",
					dataType: "json",
					url: l10n.ajaxurl,
					data: {
						action: "cmb2_oembed_handler",
						oembed_url: oembed_url,
						oembed_width: oembed_width > 300 ? oembed_width : 300,
						field_id: field_id,
						object_id: $obj.data("objectid"),
						object_type: $obj.data("objecttype"),
						cmb2_ajax_nonce: l10n.ajax_nonce
					},
					success: function (response) {
						cmb.log(response), cmb.spinner($context, !0), $embed_wrap.html(response.data)
					}
				})
			}, 500)
		}
	}, cmb.trigger = function (evtName) {
		var args = Array.prototype.slice.call(arguments, 1);
		args.push(cmb), $document.trigger(evtName, args)
	}, cmb.triggerElement = function ($el, evtName) {
		var args = Array.prototype.slice.call(arguments, 2);
		args.push(cmb), $el.trigger(evtName, args)
	}, cmb.replaceLast = function (string, search, replace) {
		var n = string.lastIndexOf(search);
		return string.slice(0, n) + string.slice(n).replace(search, replace)
	}, cmb.getFieldArg = function (hash, arg) {
		return cmb.getField(hash)[arg]
	}, cmb.getField = function (hash) {
		return hash = hash instanceof jQuery ? hash.data("hash") : hash, hash && l10n.fields[hash] ? l10n.fields[hash] : {}
	}, $(cmb.init)
}(window, document, jQuery, window.CMB2), window.CMB2 = window.CMB2 || {}, window.CMB2.wysiwyg = window.CMB2.wysiwyg || {}, function (window, document, $, wysiwyg, undefined) {
	"use strict";

	function delayedInit() {
		0 === toBeDestroyed.length ? toBeInitialized.forEach(function (toInit) {
			toBeInitialized.splice(toBeInitialized.indexOf(toInit), 1), wysiwyg.init.apply(wysiwyg, toInit)
		}) : window.setTimeout(delayedInit, 100)
	}

	function delayedDestroy() {
		toBeDestroyed.forEach(function (id) {
			toBeDestroyed.splice(toBeDestroyed.indexOf(id), 1), wysiwyg.destroy(id)
		})
	}

	function getGroupData(data) {
		var groupid = data.groupid, fieldid = data.fieldid;
		return all[groupid] && all[groupid][fieldid] || (all[groupid] = all[groupid] || {}, all[groupid][fieldid] = {
			template: wp.template("cmb2-wysiwyg-" + groupid + "-" + fieldid),
			defaults: {
				mce: $.extend({}, tinyMCEPreInit.mceInit["cmb2_i_" + groupid + fieldid]),
				qt: $.extend({}, tinyMCEPreInit.qtInit["cmb2_i_" + groupid + fieldid])
			}
		}, delete tinyMCEPreInit.mceInit["cmb2_i_" + groupid + fieldid], delete tinyMCEPreInit.qtInit["cmb2_i_" + groupid + fieldid]), all[groupid][fieldid]
	}

	function initOptions(options) {
		var prop, newSettings, newQTS, nameRegex = new RegExp("cmb2_n_" + options.groupid + options.fieldid, "g"),
			idRegex = new RegExp("cmb2_i_" + options.groupid + options.fieldid, "g");
		if (void 0 === tinyMCEPreInit.mceInit[options.id]) {
			newSettings = $.extend({}, options.defaults.mce);
			for (prop in newSettings) "string" == typeof newSettings[prop] && (newSettings[prop] = newSettings[prop].replace(idRegex, options.id).replace(nameRegex, options.name));
			tinyMCEPreInit.mceInit[options.id] = newSettings
		}
		if (void 0 === tinyMCEPreInit.qtInit[options.id]) {
			newQTS = $.extend({}, options.defaults.qt);
			for (prop in newQTS) "string" == typeof newQTS[prop] && (newQTS[prop] = newQTS[prop].replace(idRegex, options.id).replace(nameRegex, options.name));
			tinyMCEPreInit.qtInit[options.id] = newQTS
		}
	}

	var toBeDestroyed = [], toBeInitialized = [], all = wysiwyg.all = {};
	wysiwyg.initAll = function () {
		var $this, data, initiated;
		$(".cmb2-wysiwyg-placeholder").each(function () {
			$this = $(this), data = $this.data(), data.groupid && (data.id = $this.attr("id"), data.name = $this.attr("name"), data.value = $this.val(), wysiwyg.init($this, data, !1), initiated = !0)
		}), !0 === initiated && (void 0 !== window.QTags && window.QTags._buttonsInit(), $(document).on("cmb2_add_row", wysiwyg.addRow).on("cmb2_remove_group_row_start", wysiwyg.destroyRowEditors).on("cmb2_shift_rows_start", wysiwyg.shiftStart).on("cmb2_shift_rows_complete", wysiwyg.shiftComplete))
	}, wysiwyg.addRow = function (evt, $row) {
		wysiwyg.initRow($row, evt)
	}, wysiwyg.destroyRowEditors = function (evt, $btn) {
		wysiwyg.destroy($btn.parents(".cmb-repeatable-grouping").find(".wp-editor-area").attr("id"))
	}, wysiwyg.shiftStart = function (evt, $btn, $from, $to) {
		$from.add($to).find(".wp-editor-wrap textarea").each(function () {
			wysiwyg.destroy($(this).attr("id"))
		})
	}, wysiwyg.shiftComplete = function (evt, $btn, $from, $to) {
		$from.add($to).each(function () {
			wysiwyg.initRow($(this), evt)
		})
	}, wysiwyg.initRow = function ($row, evt) {
		var $toReplace, data, defVal;
		$row.find(".cmb2-wysiwyg-inner-wrap").each(function () {
			$toReplace = $(this), data = $toReplace.data(), defVal = window.CMB2.getFieldArg(data.hash, "default", ""), defVal = void 0 !== defVal && !1 !== defVal ? defVal : "", data.iterator = $row.data("iterator"), data.fieldid = data.id, data.id = data.groupid + "_" + data.iterator + "_" + data.fieldid, data.name = data.groupid + "[" + data.iterator + "][" + data.fieldid + "]", data.value = "cmb2_add_row" !== evt.type && $toReplace.find(".wp-editor-area").length ? $toReplace.find(".wp-editor-area").val() : defVal, 0 === toBeDestroyed.length ? wysiwyg.init($toReplace, data) : (toBeInitialized.push([$toReplace, data]), window.setTimeout(delayedInit, 100))
		})
	}, wysiwyg.init = function ($toReplace, data, buttonsInit) {
		if (!data.groupid) return !1;
		var mceActive = window.cmb2_l10.user_can_richedit && window.tinyMCE,
			qtActive = "function" == typeof window.quicktags;
		$.extend(data, getGroupData(data)), initOptions(data), $toReplace.replaceWith(data.template(data)), mceActive && window.tinyMCE.init(tinyMCEPreInit.mceInit[data.id]), qtActive && window.quicktags(tinyMCEPreInit.qtInit[data.id]), mceActive && $(document.getElementById(data.id)).parents(".wp-editor-wrap").removeClass("html-active").addClass("tmce-active"), !1 !== buttonsInit && void 0 !== window.QTags && window.QTags._buttonsInit()
	}, wysiwyg.destroy = function (id) {
		if (window.cmb2_l10.user_can_richedit && window.tinyMCE) {
			var editor = tinyMCE.get(id);
			null !== editor && void 0 !== editor ? (editor.destroy(), void 0 === tinyMCEPreInit.mceInit[id] && delete tinyMCEPreInit.mceInit[id], void 0 === tinyMCEPreInit.qtInit[id] && delete tinyMCEPreInit.qtInit[id]) : -1 === toBeDestroyed.indexOf(id) && (toBeDestroyed.push(id), window.setTimeout(delayedDestroy, 100))
		}
	}, $(document).on("cmb_init", wysiwyg.initAll)
}(window, document, jQuery, window.CMB2.wysiwyg);