(function () {
    'use strict';

    var mediaPattern = /(?:^|[\[_-])(image|img|photo|picture|background|bg|logo|media|video)(?:\]|[_-]|$)/i;
    var technicalPattern = /^(section\s*id|id\s+секции|якорь|anchor|css|svg|aria-label|доп\.?s*класс|класс\s+секции|иконка\s*\((svg|fa))/i;

    function insertAfter(reference, node) {
        reference.parentNode.insertBefore(node, reference.nextSibling);
    }

    function mediaIdentity(input) {
        return [input.name, input.id, input.className].join(' ');
    }

    function looksLikeMediaInput(input) {
        if (input.hasAttribute('data-kp-media')) return true;
        if (!input.matches('input[type="text"], input[type="url"]')) return false;
        return mediaPattern.test(mediaIdentity(input)) && !/(alt|aria|icon|svg)/i.test(mediaIdentity(input));
    }

    function setupMediaInput(input) {
        if (!looksLikeMediaInput(input) || input.dataset.kpMediaReady) return;
        input.dataset.kpMediaReady = '1';
        input.classList.add('kp-media-raw');
        var mediaType = input.dataset.kpMediaType === 'video' ? 'video' : 'image';
        var mediaLabel = mediaType === 'video' ? 'Видео' : 'Изображение';

        var control = document.createElement('div');
        control.className = 'kp-media-control kp-media-control--' + mediaType;
        control.innerHTML =
            '<div class="kp-media-control__preview"><span>' + mediaLabel + ' не выбрано</span></div>' +
            '<div class="kp-media-control__actions">' +
                '<button type="button" class="button button-primary" data-kp-media-select>Выбрать ' + (mediaType === 'video' ? 'видео' : 'изображение') + '</button>' +
                '<button type="button" class="button" data-kp-media-remove>Убрать</button>' +
            '</div>';
        insertAfter(input, control);

        var preview = control.querySelector('.kp-media-control__preview');
        var remove = control.querySelector('[data-kp-media-remove]');

        function refresh() {
            var value = input.value.trim();
            preview.textContent = '';
            if (value) {
                if (mediaType === 'video') {
                    var video = document.createElement('video');
                    video.src = value;
                    video.muted = true;
                    video.controls = true;
                    video.preload = 'metadata';
                    preview.appendChild(video);
                } else {
                    var image = document.createElement('img');
                    image.src = value;
                    image.alt = '';
                    preview.appendChild(image);
                }
            } else {
                var empty = document.createElement('span');
                empty.textContent = mediaLabel + ' не выбрано';
                preview.appendChild(empty);
            }
            remove.disabled = !value;
        }

        control.querySelector('[data-kp-media-select]').addEventListener('click', function () {
            if (!window.wp || typeof window.wp.media !== 'function') {
                window.alert('Медиабиблиотека WordPress не загрузилась. Обновите страницу и попробуйте снова.');
                return;
            }
            var previousFrame = window.wp.media.frame;
            var frame = window.wp.media({
                title: mediaType === 'video' ? 'Выберите фоновое видео' : 'Выберите изображение',
                button: { text: mediaType === 'video' ? 'Использовать видео' : 'Использовать изображение' },
                library: { type: mediaType },
                multiple: false
            });
            function restoreMediaFrame() {
                if (window.wp.media.frame !== frame) return;
                if (previousFrame) window.wp.media.frame = previousFrame;
                else delete window.wp.media.frame;
            }
            frame.on('select', function () {
                var attachment = frame.state().get('selection').first().toJSON();
                input.value = attachment.url || '';
                input.dispatchEvent(new Event('input', { bubbles: true }));
                input.dispatchEvent(new Event('change', { bubbles: true }));
                refresh();
            });
            frame.on('close', restoreMediaFrame);
            frame.open();
        });

        remove.addEventListener('click', function () {
            input.value = '';
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
            refresh();
        });
        input.addEventListener('change', refresh);
        refresh();

        Array.prototype.forEach.call(input.parentNode.querySelectorAll('button'), function (button) {
            if (button.closest('.kp-media-control')) return;
            if (/выбрать|загруз|фото|видео|фон/i.test(button.textContent)) button.classList.add('kp-legacy-media-button');
        });
    }

    function cleanLabel(label) {
        var text = label.textContent.trim();
        if (/html/i.test(text)) {
            label.textContent = text.replace(/\s*\((?:можно\s+)?html[^)]*\)/i, '').replace(/\s*—\s*html.*$/i, '').trim();
        }
        text = label.textContent.trim();
        if (/^Аннотация\s*\(eyebrow\)$/i.test(text) || /^Надсекция\s*\(eyebrow\)$/i.test(text)) {
            label.textContent = 'Надзаголовок';
        } else if (/^Заголовок\s*\(до gold-текста\)$/i.test(text)) {
            label.textContent = 'Основная часть заголовка';
        } else if (/^Gold-текст/i.test(text)) {
            label.textContent = 'Выделенная часть заголовка';
        } else if (/^Eyebrow\s*\(/i.test(text)) {
            label.textContent = 'Надзаголовок';
        } else if (/^Подзаголовок\s*\(lead\)$/i.test(text)) {
            label.textContent = 'Подзаголовок';
        }
        return label.textContent.trim();
    }

    function setupLegacyFieldGrids(root) {
        var fieldClasses = /^(?:field-group|ctr-field|hm-field|aa-field|as-field|ap-field|am-field|ol-field|rl-field|sr-field|sg-field|cb-field|sa-field|promo-field|fbf-field|hero-field|pl-field|si-field|req-field|wg-field|sp-field)$/;
        Array.prototype.forEach.call(root.querySelectorAll('[data-kp-body], .hm-group, .ctr-group'), function (container) {
            if (container.dataset.kpLegacyGridReady) return;
            container.dataset.kpLegacyGridReady = '1';
            var run = [];

            function flush() {
                if (!run.length) return;
                var grid = document.createElement('div');
                grid.className = 'kp-legacy-grid';
                run[0].parentNode.insertBefore(grid, run[0]);
                run.forEach(function (field) { grid.appendChild(field); });
                run = [];
            }

            Array.prototype.slice.call(container.children).forEach(function (child) {
                var isLegacyField = Array.prototype.some.call(child.classList, function (className) {
                    return fieldClasses.test(className);
                });
                if (isLegacyField) {
                    run.push(child);
                } else {
                    flush();
                }
            });
            flush();
        });
    }

    function setupSubsections(root) {
        Array.prototype.forEach.call(root.querySelectorAll('.hm-group, .ctr-group'), function (group) {
            if (group.dataset.kpSubsectionReady) return;
            group.dataset.kpSubsectionReady = '1';
            var heading = group.querySelector(':scope > h4');
            if (!heading) {
                var firstParagraph = group.querySelector(':scope > p:first-child');
                if (firstParagraph && firstParagraph.querySelector('strong')) heading = firstParagraph;
            }
            if (!heading) return;

            var title = heading.textContent.trim();
            var content = document.createElement('div');
            content.className = 'kp-subsection__content';
            Array.prototype.slice.call(group.children).forEach(function (child) {
                if (child !== heading) content.appendChild(child);
            });

            var toggle = document.createElement('button');
            toggle.type = 'button';
            toggle.className = 'kp-subsection__toggle';
            toggle.innerHTML = '<span></span><i aria-hidden="true"></i>';
            toggle.querySelector('span').textContent = title;
            heading.textContent = '';
            heading.className = 'kp-subsection__head';
            heading.appendChild(toggle);
            group.appendChild(content);

            var siblings = Array.prototype.slice.call(group.parentNode.querySelectorAll(':scope > .hm-group, :scope > .ctr-group'));
            var expanded = siblings.indexOf(group) === 0;
            function setExpanded(next) {
                toggle.setAttribute('aria-expanded', next ? 'true' : 'false');
                content.hidden = !next;
                group.classList.toggle('is-open', next);
            }
            toggle.addEventListener('click', function () {
                setExpanded(toggle.getAttribute('aria-expanded') !== 'true');
            });
            setExpanded(expanded);
        });
    }

    function markTechnicalFields(sectionBody) {
        var count = 0;
        Array.prototype.forEach.call(sectionBody.querySelectorAll('label'), function (label) {
            var labelText = cleanLabel(label);
            if (!technicalPattern.test(labelText)) return;
            var field = label.closest('[data-kp-technical], .field-group, .hm-field, .cb-field, .si-field, .req-field, p') || label.parentElement;
            if (!field || field.closest('.kp-technical-toggle')) return;
            field.classList.add('kp-technical-field');
            count += 1;
        });

        Array.prototype.forEach.call(sectionBody.querySelectorAll('[data-kp-technical]'), function (field) {
            if (!field.classList.contains('kp-technical-field')) {
                field.classList.add('kp-technical-field');
                count += 1;
            }
        });

        if (!count || sectionBody.querySelector('.kp-technical-toggle')) return;
        var toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'button-link kp-technical-toggle';
        toggle.textContent = 'Показать технические настройки (' + count + ')';
        toggle.addEventListener('click', function () {
            var visible = sectionBody.classList.toggle('show-technical-fields');
            toggle.textContent = (visible ? 'Скрыть' : 'Показать') + ' технические настройки (' + count + ')';
        });
        sectionBody.appendChild(toggle);
    }

    function updateRepeater(repeater) {
        var items = Array.prototype.slice.call(repeater.querySelectorAll(':scope > [data-kp-repeater-list] > [data-kp-repeater-item]'));
        items.forEach(function (item, index) {
            var up = item.querySelector(':scope > .kp-repeater__item-head [data-kp-item-up]');
            var down = item.querySelector(':scope > .kp-repeater__item-head [data-kp-item-down]');
            if (up) up.disabled = index === 0;
            if (down) down.disabled = index === items.length - 1;
        });
    }

    function replaceIndex(item, repeater, oldIndex, newIndex) {
        var base = repeater.dataset.kpRepeaterName;
        var oldToken = base + '[' + oldIndex + ']';
        var newToken = base + '[' + newIndex + ']';
        Array.prototype.forEach.call(item.querySelectorAll('[name]'), function (field) {
            field.name = field.name.replace(oldToken, newToken);
        });
    }

    function prepareRepeaterItem(item) {
        Array.prototype.forEach.call(item.querySelectorAll('.kp-media-control'), function (control) { control.remove(); });
        Array.prototype.forEach.call(item.querySelectorAll('input, textarea, select'), function (field) {
            delete field.dataset.kpMediaReady;
        });
        enhanceContent(item);
    }

    function setupRepeater(repeater) {
        if (repeater.dataset.kpRepeaterReady) return;
        repeater.dataset.kpRepeaterReady = '1';
        var list = repeater.querySelector(':scope > [data-kp-repeater-list]');
        var template = repeater.querySelector(':scope > template[data-kp-repeater-template]');
        if (!list || !template) return;

        repeater.addEventListener('click', function (event) {
            var item = event.target.closest('[data-kp-repeater-item]');
            if (event.target.closest('[data-kp-repeater-add]')) {
                var index = parseInt(repeater.dataset.kpNext || '0', 10);
                repeater.dataset.kpNext = index + 1;
                var holder = document.createElement('div');
                holder.innerHTML = template.innerHTML.replace(/__INDEX__/g, String(index));
                var newItem = holder.firstElementChild;
                list.appendChild(newItem);
                prepareRepeaterItem(newItem);
                updateRepeater(repeater);
                return;
            }
            if (!item) return;
            if (event.target.closest('[data-kp-item-delete]')) {
                if (window.confirm('Удалить этот элемент? Заполненные в нём данные будут удалены после сохранения страницы.')) {
                    item.remove();
                    updateRepeater(repeater);
                }
                return;
            }
            if (event.target.closest('[data-kp-item-duplicate]')) {
                var firstNamed = item.querySelector('[name]');
                var match = firstNamed && firstNamed.name.match(new RegExp(repeater.dataset.kpRepeaterName.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\[(\\d+)\\]'));
                var oldIndex = match ? match[1] : '';
                var newIndex = parseInt(repeater.dataset.kpNext || '0', 10);
                repeater.dataset.kpNext = newIndex + 1;
                var clone = item.cloneNode(true);
                replaceIndex(clone, repeater, oldIndex, newIndex);
                item.parentNode.insertBefore(clone, item.nextSibling);
                prepareRepeaterItem(clone);
                updateRepeater(repeater);
                return;
            }
            if (event.target.closest('[data-kp-item-up]') && item.previousElementSibling) {
                list.insertBefore(item, item.previousElementSibling);
            } else if (event.target.closest('[data-kp-item-down]') && item.nextElementSibling) {
                list.insertBefore(item.nextElementSibling, item);
            }
            updateRepeater(repeater);
        });

        repeater.addEventListener('input', function (event) {
            if (!event.target.matches('[data-kp-summary-source]')) return;
            var item = event.target.closest('[data-kp-repeater-item]');
            var summary = item && item.querySelector('[data-kp-item-summary]');
            if (summary) summary.textContent = event.target.value.trim() || 'Новый элемент';
        });
        updateRepeater(repeater);
    }

    function setupAwardBadgeTypes(root) {
        Array.prototype.forEach.call(root.querySelectorAll('[data-kp-award-badge-type]'), function (select) {
            if (select.dataset.kpAwardBadgeReady) return;
            select.dataset.kpAwardBadgeReady = '1';
            var item = select.closest('[data-kp-repeater-item]');
            if (!item) return;

            function refresh() {
                var isIframe = select.value === 'iframe';
                var imageFields = item.querySelector('[data-kp-award-image]');
                var iframeFields = item.querySelector('[data-kp-award-iframe]');
                if (imageFields) imageFields.hidden = isIframe;
                if (iframeFields) iframeFields.hidden = !isIframe;
            }

            select.addEventListener('change', refresh);
            refresh();
        });
    }

    function guardLegacyDeletes(root) {
        root.addEventListener('click', function (event) {
            var button = event.target.closest('button, .button, [role="button"]');
            if (!button || button.closest('[data-kp-repeater]') || button.dataset.kpDeleteConfirmed) return;
            var text = (button.textContent || button.title || '').trim();
            var identity = [button.className, button.id].join(' ');
            if (!/удалить/i.test(text) && !/(^|\s)(remove|delete|del|rm)(-|_|\s|$)/i.test(identity)) return;
            if (!window.confirm('Удалить этот элемент? Изменение вступит в силу после сохранения страницы.')) {
                event.preventDefault();
                event.stopImmediatePropagation();
                return;
            }
            button.dataset.kpDeleteConfirmed = '1';
        }, true);
    }

    function enhanceContent(root) {
        setupLegacyFieldGrids(root);
        setupSubsections(root);
        Array.prototype.forEach.call(root.querySelectorAll('input'), setupMediaInput);
        Array.prototype.forEach.call(root.querySelectorAll('[data-kp-repeater]'), setupRepeater);
        setupAwardBadgeTypes(root);
        Array.prototype.forEach.call(root.querySelectorAll('[data-kp-body]'), markTechnicalFields);
    }

    function init() {
        var builder = document.querySelector('[data-karpovka-builder]');
        var roots = Array.prototype.slice.call(document.querySelectorAll('[data-karpovka-builder], [data-kp-content-controls]')).filter(function (root, index, all) {
            return !all.some(function (candidate) { return candidate !== root && candidate.contains(root); });
        });
        if (!roots.length) return;
        roots.forEach(function (root) {
            enhanceContent(root);
            guardLegacyDeletes(root);
        });
        var scope = builder || roots[0];
        var form = scope.closest('form');
        if (form) {
            form.addEventListener('submit', function (event) {
                Array.prototype.forEach.call(scope.querySelectorAll('.kp-field-error'), function (field) { field.classList.remove('kp-field-error'); });
                Array.prototype.forEach.call(scope.querySelectorAll('.kp-validation-message'), function (message) { message.remove(); });
                var invalid = Array.prototype.filter.call(scope.querySelectorAll('[data-kp-required]'), function (field) {
                    var section = field.closest('[data-kp-section]');
                    var enabled = !section || !section.querySelector('[data-kp-enabled]') || section.querySelector('[data-kp-enabled]').checked;
                    return enabled && !field.value.trim();
                });
                if (!invalid.length) return;
                event.preventDefault();
                invalid.forEach(function (field) { field.classList.add('kp-field-error'); });
                var first = invalid[0];
                var section = first.closest('[data-kp-section]');
                if (section) {
                    var expand = section.querySelector('[data-kp-expand]');
                    var body = section.querySelector('[data-kp-body]');
                    if (expand) expand.setAttribute('aria-expanded', 'true');
                    if (body) body.hidden = false;
                }
                var message = document.createElement('div');
                message.className = 'kp-validation-message';
                message.textContent = 'Заполните обязательные поля: ' + invalid.map(function (field) { return field.dataset.kpRequired; }).filter(Boolean).join(', ') + '.';
                var targetBody = first.closest('[data-kp-body]');
                if (targetBody) targetBody.insertBefore(message, targetBody.firstChild);
                first.scrollIntoView({ behavior: 'smooth', block: 'center' });
                first.focus();
            });
        }
        var observer = new MutationObserver(function (records) {
            records.forEach(function (record) {
                Array.prototype.forEach.call(record.addedNodes, function (node) {
                    if (node.nodeType === 1) enhanceContent(node);
                });
            });
        });
        roots.forEach(function (root) {
            observer.observe(root, { childList: true, subtree: true });
        });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
