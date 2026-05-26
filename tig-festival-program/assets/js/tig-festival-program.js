(function () {
    "use strict";

    // ââ Tooltip âââââââââââââââââââââââââââââââââââââââââââââââââââââââââââââ
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

    // ââ FÃ¼lek (day tabs) ââââââââââââââââââââââââââââââââââââââââââââââââââââ
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

    // ââ AktuÃ¡lis idÅpont kiemelÃ©s âââââââââââââââââââââââââââââââââââââââââââ
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

    // ââ Init ââââââââââââââââââââââââââââââââââââââââââââââââââââââââââââââââ
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

            // FÃ¼lek
            initTabs(wrapper);

            // AktuÃ¡lis kiemelÃ©s
            highlightCurrentRow(wrapper);
        });

        // FrissÃ­tÃ©s percenkÃ©nt
        setInterval(function () {
            document.querySelectorAll(".tig-program").forEach(highlightCurrentRow);
        }, 60000);


    // ── Export ───────────────────────────────────────────────────────────────
    var exportBtn = document.querySelector('.tig-export-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function () {
            var nonce   = exportBtn.getAttribute('data-nonce');
            var ajaxUrl = exportBtn.getAttribute('data-ajax-url');
            exportBtn.disabled = true;
            exportBtn.textContent = 'Exportálás...';

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
                exportBtn.innerHTML = '&#8595; Exportálás (JSON)';
            });
        });
    }

    // ── Import ───────────────────────────────────────────────────────────────
    var importFile   = document.querySelector('.tig-import-file');
    var importHidden = document.querySelector('.tig-import-json-hidden');
    var importTrigger = document.querySelector('.tig-import-trigger');
    var importForm   = importFile && importFile.closest('form');

    if (importFile && importHidden && importForm) {
        importFile.addEventListener('change', function () {
            var file = importFile.files[0];
            if (!file) return;
            if (!file.name.endsWith('.json')) { alert('Csak .json fájlt lehet importálni.'); return; }

            var reader = new FileReader();
            reader.onload = function (e) {
                try {
                    var parsed = JSON.parse(e.target.result);
                    importHidden.value = JSON.stringify(parsed);
                    importTrigger.value = '1';
                    importForm.action = importForm.action.replace('tig_festival_program_save', 'tig_festival_program_import');
                    if (window.confirm('Biztosan importálod a fájlt? A jelenlegi program felülíródik.')) {
                        importForm.submit();
                    } else {
                        importHidden.value = '';
                        importTrigger.value = '0';
                        importForm.action = importForm.action.replace('tig_festival_program_import', 'tig_festival_program_save');
                    }
                } catch (err) {
                    alert('Érvénytelen JSON fájl.');
                }
            };
            reader.readAsText(file);
            importFile.value = '';
        });
    }

    });
})();
