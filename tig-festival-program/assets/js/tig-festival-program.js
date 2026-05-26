(function () {
    "use strict";

    // ── Tooltip ─────────────────────────────────────────────────────────────
    var tooltip = null;

    function createTooltip() {
        var el = document.createElement("div");
        el.id = "tig-venue-tooltip";
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
        tooltip.classList.add("tig-tooltip-visible");
        tooltip.setAttribute("aria-hidden", "false");
        positionTooltip(target);
    }

    function hideTooltip() {
        if (!tooltip) return;
        tooltip.classList.remove("tig-tooltip-visible");
        tooltip.setAttribute("aria-hidden", "true");
    }

    function positionTooltip(target) {
        if (!tooltip) return;
        var rect   = target.getBoundingClientRect();
        var scrollY = window.scrollY || window.pageYOffset;
        var scrollX = window.scrollX || window.pageXOffset;
        var tipW   = tooltip.offsetWidth;
        var left   = rect.left + scrollX + rect.width / 2 - tipW / 2;
        var top    = rect.top  + scrollY - tooltip.offsetHeight - 8;
        left = Math.max(8, Math.min(left, window.innerWidth - tipW - 8));
        tooltip.style.left = left + "px";
        tooltip.style.top  = top  + "px";
    }

    // ── Fülek (day tabs) ────────────────────────────────────────────────────
    function initTabs(wrapper) {
        var tabs  = wrapper.querySelectorAll(".tig-day-tab");
        var panels = wrapper.querySelectorAll(".tig-program-day");
        if (!tabs.length) return;

        tabs.forEach(function (tab) {
            tab.addEventListener("click", function () {
                var target = tab.getAttribute("data-tig-tab");
                tabs.forEach(function (t) {
                    t.classList.remove("tig-day-tab--active");
                    t.setAttribute("aria-selected", "false");
                });
                panels.forEach(function (p) { p.classList.remove("tig-program-day--active"); });
                tab.classList.add("tig-day-tab--active");
                tab.setAttribute("aria-selected", "true");
                var panel = document.getElementById(target);
                if (panel) panel.classList.add("tig-program-day--active");
            });
        });
    }

    // ── Aktuális időpont kiemelés ───────────────────────────────────────────
    function timeToMinutes(str) {
        if (!str) return null;
        var parts = str.split(":");
        if (parts.length < 2) return null;
        return parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
    }

    function highlightCurrentRow(wrapper) {
        var now = new Date();
        var currentMin = now.getHours() * 60 + now.getMinutes();
        var rows = wrapper.querySelectorAll(".tig-program-day--active .tig-mobile-row, .tig-program-day--active tbody tr");
        var times = [];
        rows.forEach(function (row) {
            var timeEl = row.querySelector(".tig-time span, .tig-mobile-time");
            var t = timeEl ? timeToMinutes(timeEl.textContent.trim().substring(0, 5)) : null;
            times.push({row: row, min: t});
        });
        var currentRow = null;
        for (var i = 0; i < times.length; i++) {
            var next = times[i + 1];
            if (times[i].min !== null && times[i].min <= currentMin && (!next || next.min === null || next.min > currentMin)) {
                currentRow = times[i].row;
                break;
            }
        }
        rows.forEach(function (r) { r.classList.remove("tig-row--current"); });
        if (currentRow) {
            currentRow.classList.add("tig-row--current");
            currentRow.scrollIntoView({behavior: "smooth", block: "nearest"});
        }
    }

    // ── Init ────────────────────────────────────────────────────────────────
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".tig-program").forEach(function (wrapper) {
            // Tooltip
            wrapper.querySelectorAll(".tig-has-venue-tooltip").forEach(function (el) {
                el.removeAttribute("title");
                if (!el.hasAttribute("tabindex")) el.setAttribute("tabindex", "0");
                el.addEventListener("mouseenter", function () { showTooltip(el); });
                el.addEventListener("mouseleave", hideTooltip);
                el.addEventListener("focus",      function () { showTooltip(el); });
                el.addEventListener("blur",       hideTooltip);
            });

            // Fülek
            initTabs(wrapper);

            // Aktuális kiemelés
            highlightCurrentRow(wrapper);
        });

        // Frissítés percenként
        setInterval(function () {
            document.querySelectorAll(".tig-program").forEach(highlightCurrentRow);
        }, 60000);
    });
})();
