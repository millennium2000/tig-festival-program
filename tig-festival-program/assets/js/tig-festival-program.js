(function () {
    "use strict";

    // Ã¢ÂÂÃ¢ÂÂ Tooltip Ã¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂ
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

    // Ã¢ÂÂÃ¢ÂÂ FÃÂ¼lek (day tabs) Ã¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂ
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

    // Ã¢ÂÂÃ¢ÂÂ AktuÃÂ¡lis idÃÂpont kiemelÃÂ©s Ã¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂ
    function timeToMinutes(str) {
        if (!str) return null;
        var parts = str.split(":");
        if (parts.length < 2) return null;
        return parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
    }

    function highlightCurrentRow(wrapper, scroll) {
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
            if (scroll) { currentRow.scrollIntoView({behavior: "smooth", block: "nearest"}); }
        }
        return !!currentRow;
    }

    // Ã¢ÂÂÃ¢ÂÂ Init Ã¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂÃ¢ÂÂ
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

            // FÃÂ¼lek
            initTabs(wrapper);

            // AktuÃÂ¡lis kiemelÃÂ©s
            // "Ugrás az aktuális időponthoz" gomb
            var jumpBtn = wrapper.querySelector(".tig-jump-now-btn");
            if (jumpBtn) {
                jumpBtn.addEventListener("click", function () {
                    var found = highlightCurrentRow(wrapper, true);
                    if (!found) {
                        jumpBtn.textContent = "Nincs aktuális időpont";
                        setTimeout(function () { jumpBtn.innerHTML = "&#9654; Most"; }, 2000);
                    }
                });
            }

            highlightCurrentRow(wrapper, false);
        });

        // FrissÃÂ­tÃÂ©s percenkÃÂ©nt
        setInterval(function () {
            document.querySelectorAll(".tig-program").forEach(function(w) { highlightCurrentRow(w, false); });
        }, 60000);


    // ââ Export âââââââââââââââââââââââââââââââââââââââââââââââââââââââââââââââ
    var exportBtn = document.querySelector('.tig-export-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function () {
            var nonce   = exportBtn.getAttribute('data-nonce');
            var ajaxUrl = exportBtn.getAttribute('data-ajax-url');
            exportBtn.disabled = true;
            exportBtn.textContent = 'ExportÃ¡lÃ¡s...';

            fetch(ajaxUrl, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=tig_festival_program_export&nonce=' + encodeURIComponent(nonce)
            })
            .then(function (r) { return r.json(); })
            .then(function (resp) {
                if (!resp.success) { alert('Export hiba: ' + (resp.data && resp.data.message ? resp.data.message : 'Ismeretlen hiba')); return; }
                var blob = new Blob([JSON.stringify(resp.data, null, 2)], {type: 'application/json'});
                var url  = URL.createObjectURL(blob);
                var a    = document.createElement('a');
                var date = new Date().toISOString().substring(0, 10);
                a.href     = url;
                a.download = 'tig-program-export-' + date + '.json';
                document.body.appendChild(a);
                a.click();
                setTimeout(function () { URL.revokeObjectURL(url); a.remove(); }, 1000);
            })
            .catch(function (e) { alert('Export hiba: ' + e.message); })
            .finally(function () {
                exportBtn.disabled = false;
                exportBtn.innerHTML = '&#8595; ExportÃ¡lÃ¡s (JSON)';
            });
        });
    }

    // ââ Import âââââââââââââââââââââââââââââââââââââââââââââââââââââââââââââââ
    var importFile   = document.querySelector('.tig-import-file');
    var importHidden = document.querySelector('.tig-import-json-hidden');
    var importTrigger = document.querySelector('.tig-import-trigger');
    var importForm   = importFile && importFile.closest('form');

    if (importFile && importHidden && importForm) {
        importFile.addEventListener('change', function () {
            var file = importFile.files[0];
            if (!file) return;
            if (!file.name.endsWith('.json')) { alert('Csak .json fÃ¡jlt lehet importÃ¡lni.'); return; }

            var reader = new FileReader();
            reader.onload = function (e) {
                try {
                    var parsed = JSON.parse(e.target.result);
                    importHidden.value = JSON.stringify(parsed);
                    importTrigger.value = '1';
                    importForm.action = importForm.action.replace('tig_festival_program_save', 'tig_festival_program_import');
                    if (window.confirm('Biztosan importÃ¡lod a fÃ¡jlt? A jelenlegi program felÃ¼lÃ­rÃ³dik.')) {
                        importForm.submit();
                    } else {
                        importHidden.value = '';
                        importTrigger.value = '0';
                        importForm.action = importForm.action.replace('tig_festival_program_import', 'tig_festival_program_save');
                    }
                } catch (err) {
                    alert('ÃrvÃ©nytelen JSON fÃ¡jl.');
                }
            };
            reader.readAsText(file);
            importFile.value = '';
        });
    }

    });
})();
