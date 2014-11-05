(function($, window, document, undefined) {

    var opts = {

    };

    function virtualscroll(settings) {
        if (!settings || !settings.rows || !settings.rowHeight) {
            return this;
        }

        var self = this,
            rows = [],
            timer,
            viewHeight = self.height(),
            height = settings.rowHeight * settings.rows;

        var i;
        for (i = 0; i < settings.rows; i++) {
            var row = {
                el: $('<div class="virtualscroll_row"></div>').height(settings.rowHeight),
                rendered: false
            };

            rows.push(row);
            self.append(row.el);
        }

        self.scroll(function() {
            var scrollTop = self.scrollTop(),
                rowHeight = settings.rowHeight;

            var start = Math.floor(scrollTop / rowHeight);
            var end = Math.ceil((scrollTop + viewHeight) / rowHeight);

            console.log(start, end);
            var i;
            for (i = start; i <= end; i++) {
                if (rows[i] && !rows[i].rendered && typeof settings.rowRenderer === 'function') {
                    settings.rowRenderer.call(self, rows[i].el, i);
                    rows[i].rendered = true;
                }
            }
        });
    }

    $.fn.virtualscroll = virtualscroll;
}) (jQuery, window, document);