/**
 * Admin panel: "صفحه اصلی" — tabs, repeatable rows, and the WP media
 * picker for image fields. Vanilla JS, no jQuery dependency, delegated
 * from document so it keeps working after rows are cloned.
 */
(function () {
	"use strict";

	function onReady(fn) {
		if (document.readyState !== "loading") fn();
		else document.addEventListener("DOMContentLoaded", fn);
	}

	onReady(function () {
		/* Tabs ------------------------------------------------------ */
		var tabBtns = document.querySelectorAll(".ghz-tab-btn");
		function activateTab(key) {
			var panel = document.getElementById("ghz-tab-" + key);
			if (!panel) return;
			tabBtns.forEach(function (b) {
				b.classList.toggle("is-active", b.getAttribute("data-tab") === key);
			});
			document.querySelectorAll(".ghz-tab-panel").forEach(function (p) {
				p.classList.remove("is-active");
			});
			panel.classList.add("is-active");
			try {
				localStorage.setItem("ghzActiveTab", key);
			} catch (e) {}
		}
		tabBtns.forEach(function (btn) {
			btn.addEventListener("click", function () {
				activateTab(btn.getAttribute("data-tab"));
			});
		});
		try {
			var last = localStorage.getItem("ghzActiveTab");
			if (last && document.getElementById("ghz-tab-" + last)) {
				activateTab(last);
			}
		} catch (e) {}

		/* Repeaters + media picker, delegated -------------------------- */
		document.addEventListener("click", function (e) {
			var addBtn = e.target.closest(".ghz-repeater-add");
			if (addBtn) {
				e.preventDefault();
				var wrap = addBtn.closest(".ghz-repeater");
				var rows = wrap.querySelector(".ghz-repeater-rows");
				var tplEl = wrap.querySelector(".ghz-repeater-template");
				if (!tplEl) return;
				var idx = "n" + Date.now() + Math.floor(Math.random() * 1000);
				var html = tplEl.textContent.split("__INDEX__").join(idx);
				var holder = document.createElement("div");
				holder.innerHTML = html.trim();
				var newRow = holder.firstElementChild;
				if (newRow) rows.appendChild(newRow);
				return;
			}

			var removeBtn = e.target.closest(".ghz-repeater-remove");
			if (removeBtn) {
				e.preventDefault();
				var row = removeBtn.closest(".ghz-repeater-row");
				if (row) row.parentNode.removeChild(row);
				return;
			}

			var selectBtn = e.target.closest(".ghz-media-select");
			if (selectBtn) {
				e.preventDefault();
				if (!window.wp || !wp.media) return;
				var field = selectBtn.closest(".ghz-image-field");
				var input = field.querySelector(".ghz-image-input");
				var preview = field.querySelector(".ghz-image-preview");
				var frame = wp.media({
					title: "انتخاب تصویر",
					multiple: false,
					library: { type: "image" },
				});
				frame.on("select", function () {
					var att = frame.state().get("selection").first().toJSON();
					input.value = att.id;
					var src = att.sizes && att.sizes.medium ? att.sizes.medium.url : att.url;
					preview.innerHTML = '<img src="' + src + '" alt="" />';
					field.classList.add("has-image");
				});
				frame.open();
				return;
			}

			var removeMediaBtn = e.target.closest(".ghz-media-remove");
			if (removeMediaBtn) {
				e.preventDefault();
				var field2 = removeMediaBtn.closest(".ghz-image-field");
				field2.querySelector(".ghz-image-input").value = 0;
				field2.querySelector(".ghz-image-preview").innerHTML = "";
				field2.classList.remove("has-image");
			}
		});
	});
})();
