define(function (require) {
    return function () {
        var self = this;
        self.event_date = function () {
            var res = [];
            var today = new Date();
            var i = 1;
            do {
                var md = (today.getMonth() + 1) + '-' + today.getDate();
                res.push({
                    id: today.getFullYear() + '-' + md,
                    name: md
                });
                today.setDate(today.getDate() + i); // 系统会自动转换
                i++;
            } while (i <= 7);
            return res;
        }();
        self.hour = [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23];
        self.court_num = [1, 2, 3, 4, 5, 6];
        self.tennis_level = [
            {id: 1, name: '1.0'},
            {id: 2, name: '2.0'},
            {id: 3, name: '3.0'},
            {id: 4, name: '3.5'},
            {id: 5, name: '4.0'},
            {id: 6, name: '4.5'},
            {id: 7, name: '5.0'},
            {id: 8, name: '更高'}
        ];

        self.content = [
            {id: 1, name: '单打'},
            {id: 2, name: '双打'},
            {id: 3, name: '混双'},
            {id: 4, name: '练习'},
            {id: 5, name: '比赛'}
        ];

        self.sexy = [
            {id: -1, name: '不限'},
            {id: 1, name: '女'},
            {id: 2, name: '男'}
        ];

        return self;
    }();
});