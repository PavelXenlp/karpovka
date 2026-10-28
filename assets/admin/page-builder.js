(function () {
    'use strict';

    function sectionElements(builder) {
        return Array.prototype.slice.call(builder.querySelectorAll('[data-kp-section]'));
    }

    function storageKey(builder) {
        return 'karpovka-builder-open-' + (builder.getAttribute('data-kp-builder-id') || 'page');
    }

    function updateBuilderSummary(builder) {
        var sections = sectionElements(builder);
        var enabled = sections.filter(function (section) {
            var toggle = section.querySelector('[data-kp-enabled]');
            return !toggle || toggle.checked;
        }).length;
        var summary = builder.querySelector('[data-kp-section-summary]');
        if (summary) summary.textContent = sections.length + ' секций · ' + enabled + ' отображается';
    }

    function updateContentState(section) {
        var state = section.querySelector('[data-kp-content-state]');
        var body = section.querySelector('[data-kp-body]');
        if (!state || !body) return;
        var fields = Array.prototype.filter.call(body.querySelectorAll('input, textarea, select'), function (field) {
            if (!field.name || field.type === 'hidden' || field.type === 'submit' || field.type === 'button') return false;
            if (field.closest('.kp-technical-field')) return false;
            if (field.type === 'checkbox' || field.type === 'radio') return field.checked;
            return String(field.value || '').trim() !== '';
        });
        state.textContent = fields.length ? 'Заполнено полей: ' + fields.length : 'Контент не заполнен';
        state.classList.toggle('is-empty', !fields.length);
    }

    function updateOrder(builder) {
        var order = [];
        var sections = sectionElements(builder);
        sections.forEach(function (section, index) {
            order.push(section.getAttribute('data-kp-section'));
            var number = section.querySelector('[data-kp-number]');
            if (number) number.textContent = index + 1;

            var up = section.querySelector('[data-kp-move-up]');
            var down = section.querySelector('[data-kp-move-down]');
            if (up) up.disabled = index === 0;
            if (down) down.disabled = index === sections.length - 1;
        });
        var input = builder.querySelector('[data-kp-order]');
        if (input) input.value = order.join(',');
    }

    function setExpanded(section, expanded, builder, remember) {
        var button = section.querySelector('[data-kp-expand]');
        var body = section.querySelector('[data-kp-body]');
        if (!button || !body) return;
        button.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        var label = button.querySelector('span');
        if (label) label.textContent = expanded ? 'Свернуть' : 'Открыть';
        body.hidden = !expanded;
        section.classList.toggle('is-expanded', expanded);
        if (expanded) updateContentState(section);
        if (remember !== false && builder && window.sessionStorage) {
            if (expanded) sessionStorage.setItem(storageKey(builder), section.getAttribute('data-kp-section'));
            else if (sessionStorage.getItem(storageKey(builder)) === section.getAttribute('data-kp-section')) sessionStorage.removeItem(storageKey(builder));
        }
    }

    function clearDragState(builder) {
        sectionElements(builder).forEach(function (section) {
            section.classList.remove('is-dragging', 'is-drag-over');
        });
    }

    function setupBuilder(builder) {
        var list = builder.querySelector('[data-kp-sections]');
        var dragged = null;
        if (!list) return;

        sectionElements(builder).forEach(updateContentState);
        updateBuilderSummary(builder);

        list.addEventListener('dragstart', function (event) {
            if (event.target.closest('[data-kp-repeater-item]')) return;
            var handle = event.target.closest('.karpovka-builder__handle');
            if (!handle) {
                event.preventDefault();
                return;
            }
            dragged = handle.closest('[data-kp-section]');
            if (!dragged) return;
            dragged.classList.add('is-dragging');
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', dragged.getAttribute('data-kp-section'));
        });

        list.addEventListener('dragover', function (event) {
            if (!dragged) return;
            var target = event.target.closest('[data-kp-section]');
            if (!target || target === dragged) return;
            event.preventDefault();
            sectionElements(builder).forEach(function (section) { section.classList.remove('is-drag-over'); });
            target.classList.add('is-drag-over');
            var rect = target.getBoundingClientRect();
            if (event.clientY < rect.top + rect.height / 2) {
                list.insertBefore(dragged, target);
            } else {
                list.insertBefore(dragged, target.nextSibling);
            }
        });

        list.addEventListener('drop', function (event) {
            if (!dragged) return;
            event.preventDefault();
            clearDragState(builder);
            dragged = null;
            updateOrder(builder);
        });

        list.addEventListener('dragend', function () {
            clearDragState(builder);
            dragged = null;
            updateOrder(builder);
        });

        builder.addEventListener('click', function (event) {
            var expand = event.target.closest('[data-kp-expand]');
            if (expand) {
                var expandableSection = expand.closest('[data-kp-section]');
                var nextState = expand.getAttribute('aria-expanded') !== 'true';
                if (nextState && !event.shiftKey) {
                    sectionElements(builder).forEach(function (section) {
                        if (section !== expandableSection) setExpanded(section, false, builder, false);
                    });
                }
                setExpanded(expandableSection, nextState, builder, true);
                return;
            }

            var up = event.target.closest('[data-kp-move-up]');
            var down = event.target.closest('[data-kp-move-down]');
            if (up || down) {
                var movingSection = (up || down).closest('[data-kp-section]');
                if (up && movingSection.previousElementSibling) {
                    list.insertBefore(movingSection, movingSection.previousElementSibling);
                } else if (down && movingSection.nextElementSibling) {
                    list.insertBefore(movingSection.nextElementSibling, movingSection);
                }
                updateOrder(builder);
                return;
            }

            if (event.target.closest('[data-kp-collapse-all]')) {
                sectionElements(builder).forEach(function (section) { setExpanded(section, false, builder, false); });
                if (window.sessionStorage) sessionStorage.removeItem(storageKey(builder));
                return;
            }

            if (event.target.closest('[data-kp-expand-all]')) {
                sectionElements(builder).forEach(function (section) { setExpanded(section, true, builder, false); });
                return;
            }

            var header = event.target.closest('.karpovka-builder__section-head');
            if (header && !event.target.closest('button, input, label, .karpovka-builder__handle')) {
                var headerSection = header.closest('[data-kp-section]');
                var headerButton = headerSection.querySelector('[data-kp-expand]');
                if (headerButton) headerButton.click();
            }
        });

        builder.addEventListener('change', function (event) {
            if (!event.target.matches('[data-kp-enabled]')) return;
            var section = event.target.closest('[data-kp-section]');
            var enabled = event.target.checked;
            section.classList.toggle('is-disabled', !enabled);
            var status = section.querySelector('[data-kp-status]');
            if (status) status.textContent = enabled ? 'Показывается' : 'Скрыт';
            updateBuilderSummary(builder);
        });

        builder.addEventListener('input', function (event) {
            var section = event.target.closest('[data-kp-section]');
            if (section) updateContentState(section);
        });

        var search = builder.querySelector('[data-kp-section-search]');
        if (search) {
            search.addEventListener('input', function () {
                var query = search.value.trim().toLocaleLowerCase('ru');
                sectionElements(builder).forEach(function (section) {
                    var copy = section.querySelector('.karpovka-builder__section-copy');
                    var matches = !query || (copy && copy.textContent.toLocaleLowerCase('ru').indexOf(query) !== -1);
                    section.classList.toggle('is-filtered-out', !matches);
                });
            });
        }

        updateOrder(builder);
        var remembered = window.sessionStorage ? sessionStorage.getItem(storageKey(builder)) : '';
        if (remembered) {
            var rememberedSection = builder.querySelector('[data-kp-section="' + remembered.replace(/"/g, '') + '"]');
            if (rememberedSection) setExpanded(rememberedSection, true, builder, false);
        }
    }

    function init() {
        Array.prototype.forEach.call(document.querySelectorAll('[data-karpovka-builder]'), setupBuilder);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
