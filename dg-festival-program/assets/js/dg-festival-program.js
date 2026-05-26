(function () {
    "use strict";

   var tooltip = null;

   function createTooltip() {
         var el = document.createElement("div");
         el.id = "dg-venue-tooltip";
         el.setAttribute("role", "tooltip");
         el.setAttribute("aria-hidden", "true");
         document.body.appendChild(el);
         return el;
   }

   function showTooltip(target) {
         if (!tooltip) tooltip = createTooltip();
         var text = target.getAttribute("data-venue-tooltip");
         if (!text) return;
         tooltip.textContent = text;
         tooltip.classList.add("dg-tooltip-visible");
         tooltip.setAttribute("aria-hidden", "false");
         positionTooltip(target);
   }

   function hideTooltip() {
         if (!tooltip) return;
         tooltip.classList.remove("dg-tooltip-visible");
                             tooltip.setAttribute("aria-hidden", "true");
   }

   function positionTooltip(target) {
         if (!tooltip) return;
         var rect = target.getBoundingClientRect();
         var scrollY = window.scrollY || window.pageYOffset;
         var scrollX = window.scrollX || window.pageXOffset;
         var tipW = tooltip.offsetWidth;
         var left = rect.left + scrollX + rect.width / 2 - tipW / 2;
         var top = rect.top + scrollY - tooltip.offsetHeight - 8;
         left = Math.max(8, Math.min(left, window.innerWidth - tipW - 8));
         tooltip.style.left = left + "px";
         tooltip.style.top = top + "px";
   }

   document.addEventListener("DOMContentLoaded", function () {
         document.querySelectorAll(".dg-has-venue-tooltip").forEach(function (el) {
                 el.removeAttribute("title");
                 if (!el.hasAttribute("tabindex")) el.setAttribute("tabindex", "0");
                 el.addEventListener("mouseenter", function () { showTooltip(el); });
                 el.addEventListener("mouseleave", hideTooltip);
                 el.addEventListener("focus",      function () { showTooltip(el); });
                 el.addEventListener("blur",       hideTooltip);
         });
   });
})();
