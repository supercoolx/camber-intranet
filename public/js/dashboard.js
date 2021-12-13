/**
 * Created by dmi2nfc on 04.09.19.
 */

var API_CALL_URL = '/admin/ajax/';
const STATUS_SUCCESS = 'success';
const STATUS_ERROR = 'error';

function jquery_ready() {

    $.extend(!0, $.fn.dataTable.defaults, {
        dom: "<'row mai-datatable-header'<'col-sm-6'l><'col-sm-6'f>><'row mai-datatable-body'<'col-sm-12'tr>><'row mai-datatable-footer'<'col-sm-5'i><'col-sm-7'p>>"
    });

    $.extend($.fn.dataTable.ext.classes, {
        sFilterInput: "form-control form-control-sm",
        sLengthSelect: "form-control form-control-sm"
    });

    $(document).ready(function () {
        $('#table').DataTable();
    });
}


Vue.component('sp-payouts', {
    template: '#payout_tpl',
    created: function () {
        this.fetch_data()
    },
    data: function () {
        return {
            payouts: [],
            selected_id: 0,
            selected: null,
            reject_message: ''
        }

    },
    methods: {
        fetch_data: function () {
            var self = this;

            this.$root.send_get_request(API_CALL_URL + 'get_pending_payouts', [], function (data) {
                self.payouts = data;
            });
        },
        select: function (p) {
            this.selected_id = p.payout_id;
            this.selected = p;
        },

        confirm_payment: function () {
            var self = this;
            this.$root.send_post_request(API_CALL_URL + 'confirm_payment', {'id': self.selected_id}, function (data) {

                if (data.status == 'success') {
                    self.selected.status = 'accepted';
                    self.selected_id = 0;
                    self.selected = null;
                } else {
                    self.$root.message.error = data.message;
                }

            });
        },

        reject_payment: function () {
            var self = this;

            this.$root.send_post_request(API_CALL_URL + 'reject_payment', {
                'id': self.selected_id,
                'reject': this.reject_message
            }, function (data) {

                if (data.status == 'success') {
                    self.selected.status = 'rejected';
                    self.selected_id = 0;
                    self.selected = null;
                } else {
                    self.$root.message.error = data.message;
                }
            });
        }
    }
});


Vue.component('sp-balance', {
    template: '<div class="row" id="balance">\
                    <div class="col-md-12 text-center">\
                        <div class="panel">\
                        <div class="panel-body">\
                            <div class="user-display-stats">\
                            <div class="user-display-stat" v-for="b in balance"><span class="user-display-stat-counter" v-text="b.wallet_balance"></span>\
                            <span class="user-display-stat-title" v-text="b.name"></span>\
                            </div></div>\
                            <p>Доступно для вывода за день</p>\
                            <div class="progress">\
                              <div class="progress-bar" role="progressbar" :style="{\'width\': today_last + \'%\'}" v-text="today_last + \'%\'"></div>\
                            </div>\
                        </div>\
                   </div>\
                 </div>\
             </div>',

    created: function () {
        this.fetch_data()
    },
    data: function () {
        return {
            balance: {},
            today_last: 0,
        }
    },
    methods: {
        fetch_data: function () {
            var self = this;
            this.$root.send_get_request(API_CALL_URL + 'get_balance', [], function (data) {
                self.balance = data.payment_system;
                self.$root.payment_sys = data.payment_system;

                // 100 / (200 / 100)

                self.today_last = (data.payout_today_limit - data.total_payout_today) / (data.payout_today_limit / 100);
            });
        }
    }
});

Vue.component('sp-transactions', {
    template: '#transactions',

    created: function () {
        this.fetch_data();
    },
    data: function () {
        return {
            transactions: {}
        }
    },
    methods: {
        fetch_data: function () {
            var self = this;

            self.$root.send_get_request(API_CALL_URL + 'last_transactions', [], function (data) {
                self.transactions = data;
            });

            setInterval(function () {
                self.$root.send_get_request(API_CALL_URL + 'last_transactions', [], function (data) {
                    self.transactions = data;
                });

            }, 5000);
        }
    }
});


Vue.component('sp-payment-type', {
    props: ['type', 'id'],
    template: `<div>
                    <a :href="'/admin/steamtrade/' + id" v-if="type == 'steam'">
                        <i aria-hidden="true" class="fa fa-steam"></i> STEAM
                    </a>
                    <a :href="'/admin/bitcoin/' + id" v-if="type == 'bitcoin'">
                        <i aria-hidden="true" class="fa fa-bitcoin"></i> BITCOIN
                    </a>
                </div>`
});

Vue.component('sp-users', {
    template: '#last_users',
    created: function () {
        this.fetch_data();
    },
    data: function () {
        return {users: {}, search: '', sort: 'date'}
    },
    watch: {
        search: function (val, oldVal) {
            if (val.length >= 3 && val != oldVal) {
                var self = this;
                self.$root.send_get_request(API_CALL_URL + 'last_users', {'q': val}, function (data) {
                    self.users = data;
                });
            }
        }
    },
    methods: {
        fetch_data: function () {
            var self = this;

            self.$root.send_get_request(API_CALL_URL + 'last_users', [], function (data) {
                self.users = data;
            });
            setInterval(function () {

                if (self.search != '') return false;

                self.$root.send_get_request(API_CALL_URL + 'last_users', [], function (data) {
                    self.users = data;
                });
            }, 5000);
        }
    }
});


Vue.component('sp-payments', {
    template: '#payments',
    created: function () {
        this.fetch_data();
    },
    data: function () {
        return {
            payments: {},
            id: null,
            user_id: null,
            account: null,
            email: null,
            payment_type: null,
            token: null,
            ip_adress: null,
            time_range: 1,
            status: null,
            merchant_id: null,
            sort: 'date',
            limit: 200
        }
    },
    methods: {

        fetch_data: function () {
            var self = this;

            self.$root.send_post_request(API_CALL_URL + 'search_on_payments', {
                id: self.id,
                user_id: self.user_id,
                account: self.account,
                email: self.email,
                payment_type: self.payment_type,
                token: self.token,
                ip_adress: self.ip_adress,
                time_range: self.time_range,
                status: self.status,
                merchant_id: self.merchant_id,
                sort: self.sort,
                limit: self.limit
            }, function (data) {
                self.payments = data.data;
            });
        }
    }
});


Vue.component('sp-p2p_askbid', {
    template: '#p2p_askbid',
    created: function () {
        this.fetch_data();
    },
    data: function () {
        return {
            p2p_askbid: {},
            id: null,
            user_id: null,
            steamid: null,
            state: null,
            type: null,
            merchant_id: null,
            payments_id: null,
            time_range: 1,
            steamtrade_id: null,
            project: null,
            item_id: null,
            steam_market_hash_name: null,
            steam_asset_id: null,
            price: null,
            price_real: null,
            sort: 'date',
            limit: 200
        }
    },
    methods: {

        fetch_data: function () {
            var self = this;

            self.$root.send_post_request(API_CALL_URL + 'search_on_p2p_askbid', {
                id: self.id,
                user_id: self.user_id,
                steamid: self.steamid,
                state: self.state,
                type: self.type,
                merchant_id: self.merchant_id,
                payments_id: self.payments_id,
                time_range: self.time_range,
                steamtrade_id: self.steamtrade_id,
                project: self.project,
                item_id: self.item_id,
                steam_market_hash_name: self.steam_market_hash_name,
                steam_asset_id: self.steam_asset_id,
                price: self.price,
                price_real: self.price_real,
                sort: self.sort,
                limit: self.limit
            }, function (data) {
                self.p2p_askbid = data.data;
            });
        }
    }
});

var SpActivityUserPayments = Vue.component('sp-activity-user-payments', {
    props: {
        user_payments: Array,
    },
    template: `
        <div class="row">
            <table class="table table-hover table-sm">
                <thead class="thead-inverse">
                <tr>
                        <th>User ID</th>
                        <th>Merchant</th>
                        <th>Payment ID</th>
                        <th>State</th>
                        <th>Price</th>
                        <th>Time created</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="payment in user_payments">
                    <td>
                       <strong v-text="payment.account"></strong>
                    </td>                
                    <td>
                       <strong v-text="payment.merchant_name"></strong>
                    </td>
                    <td>
                        <strong v-text="payment.transaction_id"></strong>
                    </td>
                    <td>
                        <strong v-text="payment.status"></strong>
                    </td>
                    <td>
                        <strong v-text="payment.price"></strong>
                    </td>
                    <td>
                        <strong v-text="payment.time_created"></strong>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>    
    `,
    created: function () {
        console.log(this.user_payments);
        // this.fetch_data();
    },
    data: function () {
        return {}
    },
    methods: {}
});

var SpActivityUserTrades = Vue.component('sp-activity-user-trades', {
    props: {
        user_trades: Array,
    },
    computed: {
        trades: function () {
            if (this.user_trades.length == 0) return [];
            var active = this.user_trades.filter(trade => trade.state == 'active');
            var pending = this.user_trades.filter(trade => trade.state == 'pending');
            var cancelled = this.user_trades.filter(trade => trade.state == 'cancelled');
            var finished = this.user_trades.filter(trade => trade.state == 'finished');

            return [...active, ...pending, ...finished, ...cancelled];
        }
    },
    template: `
        <div class="row">
            <table class="table table-hover table-sm">
                <thead class="thead-inverse">
                <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Buyer Steamid</th>
                        <th>Merchant</th>
                        <th>Payment ID</th>
                        <th>State</th>
                        <th>Price</th>
                        <th>Item assetid</th>
                        <th>Item</th>
                        <th>Time created</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="trade in trades" :style="trade.state == 'active' ? 'color:limegreen;' : ''">
                    <td>
                       <strong v-text="trade.id"></strong>
                    </td>         
                    <td>
                       <strong v-text="trade.user_id"></strong>
                    </td>
                    <td>
                       <strong v-text="trade.steam_to_steamid"></strong>
                    </td>                                                    
                    <td>
                       <strong v-text="trade.merchant_name"></strong>
                    </td>
                    <td>
                        <strong v-text="trade.payment"></strong>
                    </td>
                    <td>
                        <strong v-text="trade.state"></strong>
                    </td>
                    <td>
                        <strong v-text="trade.price"></strong>
                    </td>
                    <td>
                        <strong v-text="trade.item_assetid"></strong>
                    </td>                    
                    <td>
                        <strong v-text="trade.item_name"></strong>
                    </td>
                    <td>
                        <strong v-text="trade.time_created"></strong>
                    </td>                    
                </tr>
                </tbody>
            </table>
        </div>    
    `,
    created: function () {
        //console.log(this.user_payments);
        // this.fetch_data();
    },
    data: function () {
        return {}
    },
    methods: {}
});

Vue.component('sp-activity', {
    template: '#activity',
    components: {
        'sp-activity-user-payments': SpActivityUserPayments,
        'sp-activity-user-trades': SpActivityUserTrades,
    },
    data: function () {
        return {
            users: [],
            payments: null,
            user_trades: [],
            user_id: null,
            steamid: null,
            merchant_id: null,
            payments_id: null,
            steamtrade_id: null,
            price: null,
            sort: 'extension',
            page: 1,
            limit: 15,
            steamid_custom: '',
            socket: null,
            currentSort: 'ext_online',
            currentSortDir: 'desc',
            websocket_response_steam: '',
            websocket_response_shadowpay: '',
            total_users: 0,
            users_ext_online_count: 0,
            users_online_count: 0,
            websocket: {
                messages: ["Events Notifications"],
                logs: [],
                status: "disconnected",
                wsServer: null
            }
        }
    },
    computed: {
        // users_online: function(){
        //     if(this.users.length == 0) return [];
        //     return this.users.sort(function(user2,user1){return user1.online-user2.online});
        // },
        // users_ext_online_count: function () {
        //     if (this.users.length == 0) return [];
        //     return this.users.filter(user => user.ext_online);
        // },
        // users_online_count: function () {
        //     if (this.users.length == 0) return [];
        //     return this.users.filter(user => user.online);
        // },
        pages: function () {
            return this.generatePageRange(this.page, this.total_page);
        },
        user_items_pagi: function () {
            return this.users;
            // var self = this;

            // var items = this.users.length > 0 ?
            //     this.users.sort((a, b) => {
            //         let modifier = 1;
            //
            //         var a_value = parseInt(a[this.currentSort], 10) && a[this.currentSort].length < 8 ? parseInt(a[this.currentSort], 10) : a[this.currentSort];
            //         var b_value = parseInt(b[this.currentSort], 10) && b[this.currentSort].length < 8 ? parseInt(b[this.currentSort], 10) : b[this.currentSort];
            //
            //         if (this.currentSortDir === 'desc') modifier = -1;
            //         if (a_value < b_value) return -1 * modifier;
            //         if (a_value > b_value) return 1 * modifier;
            //         return 0;
            //     }) : [];
            //
            // // var items = this.users.length > 0 ?
            // //     this.users.sort(function(user2,user1){return user1.online-user2.online || user1.ext_online-user2.ext_online}) : [];
            //
            // if (self.user_id > 0) {
            //     items = items.filter(function (item) {
            //         return item.id.includes(self.user_id)
            //     });
            // }
            //
            // if (self.steamid) {
            //     items = items.filter(function (item) {
            //         return item.steamid.includes(self.steamid)
            //     });
            // }
            //
            //
            // items = items.slice((this.page - 1) * this.limit, ((this.page - 1) + 1) * this.limit);
            // return items;
        },
        total_page: function () {
            var total_page = Math.ceil(this.total_users / this.limit);
            if (total_page < 2) this.page = 1;
            return total_page;
        }
    },
    created: function () {
        console.log('CREATED');
        var self = this;
        self.fetch_data();
        let timerId = setTimeout(function tick() {
            self.fetch_data();
            timerId = setTimeout(tick, 5000);
        },  5000);
        //this.ws_setup();
    },
    methods: {
        sort_icon_class: function (field_name) {
            return ['icon', field_name == this.currentSort && this.currentSortDir == 'asc' ? 's7-up-arrow' : '',
                field_name == this.currentSort && this.currentSortDir == 'desc' ? 's7-bottom-arrow' : ''];
        },
        fetch_data: function () {
            var self = this;

            self.$root.send_post_request(API_CALL_URL + 'get_all_users', {
                user_id: self.user_id,
                steamid: self.steamid,
                page: self.page,
                // merchant_id: self.merchant_id,
                // payments_id: self.payments_id,
                // price: self.price,
                current_sort: self.currentSort,
                current_sort_dir: self.currentSortDir,
                steamtrade_id: self.steamtrade_id,
                // sort: self.sort,
                limit: self.limit
            }, function (data) {
                self.users = data.users;
                self.total_users = data.users_total;
                self.users_ext_online_count = data.users_ext_online;
                self.users_online_count = data.users_online;
                //self.steamtrade = data.data;
            });
        },
        sort_column: function (s) {
            //if s == current sort, reverse
            if (s === this.currentSort) {
                this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
            }
            this.currentSort = s;
            this.fetch_data();
        },
        run_task_params: function () {
            this.websocket_response_steam = '';
            this.websocket_response_shadowpay = '';
            var steamid = this.steamid_custom;
            this.socket.send('{"cmd":"run_task_params","route":"is_auth","params":{"steamid":"' + steamid + '"}}');
        },
        user_payments: function (user) {
            var self = this;

            self.user_trades = [];
            this.$root.send_get_request(API_CALL_URL + 'get_user_payments', {user: user.id}, function (data) {
                self.payments = data;
            });
        },
        get_user_trades: function (user) {
            var self = this;
            self.payments = null;

            this.$root.send_get_request(API_CALL_URL + 'get_user_trades', {user: user.id}, function (data) {
                self.user_trades = data;
            });
        },
        ws_setup: function () {

            this.websocket.wsServer = window.wsServer;
            this.socket = new WebSocket(this.websocket.wsServer + '?admin_connect=1');

            var self = this;
            this.socket.onopen = function () {
                // debugger;
                self.websocket.status = "connected";
                self.websocket.logs.push({event: "Connected", data: self.websocket.wsServer});
                console.log("WS Connected " + self.websocket.wsServer)
            };
            this.socket.onmessage = function ({data}) {
                if (data) {
                    self.process_ws_response(data);
                }
                self.websocket.logs.push({event: "Recieved message", data});
                console.log("WS Recieved message " + data)
            };
            this.socket.onclose = function () {
                self.websocket.status = "disconnected";
                self.websocket.logs.push({event: "Disconnected", data: self.websocket.wsServer});
                console.log("WS Disconnected " + self.websocket.wsServer)
                setTimeout($this.ws_setup, 1000);
            };
            this.socket.onclose = function (evt) {
                self.websocket.logs.push({event: "WS Error occured", evt});
                console.log('WS Error occured: ' + evt);
                setTimeout(self.ws_setup, 1000);
            };
        },
        process_ws_response: function (data) {
            var self = this;
            try {
                var response = JSON.parse(data);
            } catch (e) {
                return false;
            }
            // debugger;
            switch (response.type) {
                case 'admin_msg_user_connected':
                    var steamid = response.steamid;
                    self.users = self.users.map(function (user) {
                        user.online = user.steamid == steamid ? true : user.online;
                        return user;
                    });
                    break;
                case 'admin_msg_user_disconnected':
                    var steamid = response.steamid;
                    self.users = self.users.map(function (user) {
                        user.online = user.steamid == steamid ? false : user.online;
                        return user;
                    });
                    break;
                case 'admin_msg_user_connected_to_ext':
                    var steamid = response.steamid;
                    self.users = self.users.map(function (user) {
                        user.ext_online = user.steamid == steamid ? true : user.ext_online;
                        return user;
                    });
                    break;
                case 'admin_msg_user_disconnected_from_ext':
                    var steamid = response.steamid;
                    self.users = self.users.map(function (user) {
                        user.ext_online = user.steamid == steamid ? false : user.ext_online;
                        return user;
                    });
                    break;
                case 'steam':
                    this.websocket_response_steam = response;
                    break;
                case 'shadowpay':
                    this.websocket_response_shadowpay = response;
                    break;
                case 'new_steam_trade_state':
                    var steamid = response.steam_from_steamid;
                    var trades_count = response.steam_active_trade_count;
                    var trade_id = response.steamtrade_id;
                    var trade_state = response.steamtrade_state;

                    self.users = self.users.map(function (user) {
                        user.active_trades = user.steamid == steamid ? trades_count : user.active_trades;
                        return user;
                    });

                    self.user_trades = self.user_trades.map(function (trade) {
                        trade.state = trade.id == trade_id ? trade_state : trade.state;
                        return trade;
                    });

                    if (self.user_trades && self.user_trades[0] && response.trade_info) {
                        var income_trade = response.trade_info;
                        // debugger;
                        var user_id = self.user_trades[0].user_id;
                        var found_trade = self.user_trades.filter(function (trade) {
                            return trade.id == income_trade.id;
                        });

                        if (found_trade.length == 0 && user_id == income_trade.user_id) {
                            self.user_trades.push(income_trade);
                        }
                    }
                    break;
                default:
            }
        },
        generatePageRange: function (currentPage, lastPage) {
            const delta = 1;

            if (lastPage < 2) return [1];

            const range = [];
            for (let i = Math.max(2, (currentPage - delta)); i <= Math.min((lastPage - 1), (currentPage + delta)); i += 1) {
                range.push(i);
            }

            if ((currentPage - delta) > 2) {
                range.unshift('...');
            }
            if ((currentPage + delta) < (lastPage - 1)) {
                range.push('...');
            }

            range.unshift(1);
            if (lastPage !== 1) range.push(lastPage);

            return range;

        },
        change_page: function (currentPage) {
            if (currentPage == '...') return;
            this.page = currentPage;
            this.fetch_data();
        }
    }
});

Vue.component('sp-bid-items', {
    props: ['type'],
    template: '#stats_ask_bid_items',
    components: {
        // 'sp-activity-user-payments': SpActivityUserPayments,
        // 'sp-activity-user-trades': SpActivityUserTrades,
    },
    created: function () {
        var date = new Date();
        var week_ago_date = new Date();
        week_ago_date.setDate(week_ago_date.getDate() - 7);
        this.date_end = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
        this.date_start = week_ago_date.getFullYear() + '-' + ('0' + (week_ago_date.getMonth() + 1)).slice(-2) + '-' + ('0' + week_ago_date.getDate()).slice(-2);
        this.fetch_data();
    },
    data: function () {
        return {
            date_start: null,
            date_end: null,
            sort: null,
            selected_game: '',
            items_state: '',
            data: [],
            items: [],
            page: 1,
            limit: 25,
        }
    },
    computed: {
        pages: function () {
            return this.generatePageRange(this.page, this.total_page);
        },
        items_pagi: function () {
            var self = this;
            // var items = this.users.length > 0 ?
            //     this.users.sort(function(user2,user1){return user1.online-user2.online || user1.ext_online-user2.ext_online}) : [];

            var items = self.items.slice((this.page - 1) * this.limit, ((this.page - 1) + 1) * this.limit);
            return items;
        },
        total_page: function () {
            var total_page = Math.ceil(this.items.length / this.limit);
            if (total_page < 2) this.page = 1;
            return total_page;
        }
    },
    methods: {
        fetch_data: function () {
            var self = this;
            self.$root.send_post_request(API_CALL_URL + 'get_stats_bid_items', {
                date_start: self.date_start,
                date_end: self.date_end,
                selected_game: self.selected_game,
                items_state: self.items_state,
                type: self.type
            }, function (data) {
                self.items = data;
                // generate_line(self.data, 'statistic', 'Stat per day');

            });
        },
        filter_statistic: function (event) {
            if (event) event.preventDefault();
            this.fetch_data();
        },
        user_payments: function (user) {
            var self = this;

            self.user_trades = [];
            this.$root.send_get_request(API_CALL_URL + 'get_user_payments', {user: user.id}, function (data) {
                self.payments = data;
            });
        },
        get_user_trades: function (user) {
            var self = this;
            self.payments = null;

            this.$root.send_get_request(API_CALL_URL + 'get_user_trades', {user: user.id}, function (data) {
                self.user_trades = data;
            });
        },
        generatePageRange: function (currentPage, lastPage) {
            const delta = 1;

            if (lastPage < 2) return [1];

            const range = [];
            for (let i = Math.max(2, (currentPage - delta)); i <= Math.min((lastPage - 1), (currentPage + delta)); i += 1) {
                range.push(i);
            }

            if ((currentPage - delta) > 2) {
                range.unshift('...');
            }
            if ((currentPage + delta) < (lastPage - 1)) {
                range.push('...');
            }

            range.unshift(1);
            if (lastPage !== 1) range.push(lastPage);

            return range;

        },
        change_page: function (currentPage) {
            if (currentPage == '...') return;
            this.page = currentPage;
        }
    }
});

Vue.component('sp-steamtrade', {
    template: '#steamtrade',
    created: function () {
        this.fetch_data();
    },
    data: function () {
        return {
            items: {},
            id: null,
            user_id: null,
            merchant_id: null,
            payments_id: null,
            token: null,
            state: null,
            price: null,
            price_real: null,
            steam_trade_token: null,
            steam_trade_offer_state: null,
            steam_message: null,
            steam_tradeofferid: null,
            steam_from_steamid: null,
            steam_to_steamid: null,
            custom_id: null,
            time_range: null,
            sort: 'date',
            page: 1,
            limit: 15,
            currentSort: 'time_created',
            currentSortDir: 'desc',
            max_items_limit: 500,
            //limit: 200
        }
    },
    computed: {
        pages: function () {
            return this.generatePageRange(this.page, this.total_page);
        },
        items_pagi: function () {
            var self = this;

            var items = this.items.length > 0 ?
                this.items.sort((a, b) => {
                    let modifier = 1;

                    var a_value = parseInt(a[this.currentSort], 10) && a[this.currentSort].length < 8 ? parseInt(a[this.currentSort], 10) : a[this.currentSort];
                    var b_value = parseInt(b[this.currentSort], 10) && b[this.currentSort].length < 8 ? parseInt(b[this.currentSort], 10) : b[this.currentSort];

                    if (this.currentSortDir === 'desc') modifier = -1;
                    if (a_value < b_value) return -1 * modifier;
                    if (a_value > b_value) return 1 * modifier;
                    return 0;
                }) : [];

            items = items.slice((this.page - 1) * this.limit, ((this.page - 1) + 1) * this.limit);
            return items;
        },
        total_page: function () {
            var total_page = Math.ceil(this.items.length / this.limit);
            if (total_page < 2) this.page = 1;
            return total_page;
        },
    },
    methods: {
        sort_icon_class: function (field_name) {
            return ['icon', field_name == this.currentSort && this.currentSortDir == 'asc' ? 's7-up-arrow' : '',
                field_name == this.currentSort && this.currentSortDir == 'desc' ? 's7-bottom-arrow' : ''];
        },
        fetch_data: function () {
            var self = this;

            self.$root.send_post_request(API_CALL_URL + 'search_on_steamtrade', {
                id: self.id,
                user_id: self.user_id,
                merchant_id: self.merchant_id,
                payments_id: self.payments_id,
                token: self.token,
                state: self.state,
                price: self.price,
                price_real: self.price_real,
                steam_trade_token: self.steam_trade_token,
                steam_trade_offer_state: self.steam_trade_offer_state,
                steam_message: self.steam_message,
                steam_tradeofferid: self.steam_tradeofferid,
                steam_from_steamid: self.steam_from_steamid,
                custom_id: self.custom_id,
                steam_to_steamid: self.steam_to_steamid,
                time_range: self.time_range,
                sort: self.sort,
                limit: self.max_items_limit
            }, function (data) {
                self.items = data.data;
            });
        },
        get_request: function (url) {
            var self = this;

            self.$root.send_get_request(url, {}, function (data) {
                //self.items = data.data;
                console.log(data);
                self.fetch_data();
            });
        },
        sort_column: function (s) {
            //if s == current sort, reverse
            if (s === this.currentSort) {
                this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
            }
            this.currentSort = s;
        },
        generatePageRange: function (currentPage, lastPage) {
            const delta = 1;

            if (lastPage < 2) return [1];

            const range = [];
            for (let i = Math.max(2, (currentPage - delta)); i <= Math.min((lastPage - 1), (currentPage + delta)); i += 1) {
                range.push(i);
            }

            if ((currentPage - delta) > 2) {
                range.unshift('...');
            }
            if ((currentPage + delta) < (lastPage - 1)) {
                range.push('...');
            }

            range.unshift(1);
            if (lastPage !== 1) range.push(lastPage);

            return range;

        },
        change_page: function (currentPage) {
            if (currentPage == '...') return;
            this.page = currentPage;
        }
    }
});


Vue.component('sp-items', {
    template: '#items',
    props: ['item_id'],
    created: function () {
        this.fetch_data();
    },
    data: function () {
        return {
            items: {},
            id: null,
            status: null,
            game_id: null,
            market_hash_name: null,
            payments_id: null,
            merchant_id: null,
            price: null,
            price_real: null,
            bot_steamid: null,
            user_steamid: null,
            info: null,
            time_range: null,
            sort: 'date',
            limit: 200
        }
    },
    methods: {
        fetch_data: function () {
            var self = this;

            self.$root.send_post_request(API_CALL_URL + 'search_on_items', {
                id: self.id,
                status: self.status,
                item_id: self.item_id,
                game_id: self.game_id,
                market_hash_name: self.market_hash_name,
                payments_id: self.payments_id,
                merchant_id: self.merchant_id,
                price: self.price,
                price_real: self.price_real,
                bot_steamid: self.bot_steamid,
                user_steamid: self.user_steamid,
                info: self.info,
                time_range: self.time_range,
                sort: self.sort,
                limit: self.limit
            }, function (data) {
                self.items = data.data;
            });
        },
    },
});


Vue.component('sp-moder-items', {
    template: '#moder-items',
    created: function () {
        this.get_items();
    },
    data: function () {
        return {
            bot: '',
            game: '',
            items: []
        }
    },
    methods: {

        get_items: function () {
            var self = this;

            self.$root.send_post_request(API_CALL_URL + 'get_items', {
                bot: self.bot,
                game: self.game,
            }, function (data) {
                self.items = data;
            });
        }
    }
});

Vue.component('sp-warning', {
    template: '#warning',
    created: function () {
        this.fetch_data();
    },
    data: function () {
        return {
            warnings: {},
            id: null,
            text: null,
            type_warning: null,
            date: null,
            time_range: null,
            sort: 'id',
            limit: 200
        }
    },
    methods: {
        fetch_data: function () {
            var self = this;

            self.$root.send_post_request(API_CALL_URL + 'search_on_warning', {
                id: self.id,
                text: self.text,
                type_warning: self.type_warning,
                date: self.date,
                time_range: self.time_range,
                sort: self.sort,
                limit: self.limit
            }, function (data) {
                self.warnings = data.data;
            });
        },
    },
});

Vue.component('sp-stats', {
    template: '#root',
    created: function () {
        this.fetch_data();
    },
    data: function () {
        return {
            stat_data: {},
        }
    },
    methods: {
        fetch_data: function () {
            var self = this;

            self.$root.send_post_request(API_CALL_URL + 'search_on_stats', {}, function (data) {
                generate_pie(data.shops, 'shop-stat', 'Marketplaces stats');
                generate_line(data.stats, 'statistics', 'Stat per day');

            });
        },
    },
});

Vue.component('sp-stat_steamtrades', {
    template: '#stat_steamtrades',
    created: function () {

        var date = new Date();
        var week_ago_date = new Date();
        week_ago_date.setDate(week_ago_date.getDate() - 7);
        this.date_end = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
        this.date_start = week_ago_date.getFullYear() + '-' + ('0' + (week_ago_date.getMonth() + 1)).slice(-2) + '-' + ('0' + week_ago_date.getDate()).slice(-2);
        this.fetch_data();
    },
    data: function () {
        return {
            date_start: null,
            date_end: null,
            sort: null,
            items_state: 'finished',
            data: []
        }
    },
    methods: {
        fetch_data: function () {
            var self = this;
            self.$root.send_post_request(API_CALL_URL + 'steamtrades_stats', {
                date_start: self.date_start,
                date_end: self.date_end,
                items_state: self.items_state,
            }, function (data) {
                self.data = data.stats;
                generate_line(self.data, 'statistic', 'Stat per day');

            });
        },
        filter_statistic: function (event) {
            if (event) event.preventDefault();
            this.fetch_data();
        }
    },
});


Vue.component('sp-statitems', {
    template: '#statitems',
    created: function () {

        var date = new Date();
        var week_ago_date = new Date();
        week_ago_date.setDate(week_ago_date.getDate() - 7);
        this.date_end = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
        this.date_start = week_ago_date.getFullYear() + '-' + ('0' + (week_ago_date.getMonth() + 1)).slice(-2) + '-' + ('0' + week_ago_date.getDate()).slice(-2);
        this.fetch_data();
    },
    data: function () {
        return {
            date_start: null,
            date_end: null,
            sort: null,
            selected_game: '',
            items_state: '',
            data: []
        }
    },
    methods: {
        fetch_data: function () {
            var self = this;
            self.$root.send_post_request(API_CALL_URL + 'bid_items_stats', {
                date_start: self.date_start,
                date_end: self.date_end,
                selected_game: self.selected_game,
                items_state: self.items_state,
            }, function (data) {
                self.data = data.stats;
                generate_line(self.data, 'statistic', 'Stat per day');

            });
        },
        filter_statistic: function (event) {
            if (event) event.preventDefault();
            this.fetch_data();
        }
    },
});

Vue.component('sp-logsettings', {
    template: '#log_settings',
    data: function () {
        return {
            log_settings: [],
        }
    },
    created: function () {
        this.fetch_data();
    },
    watch: {
        log_settings: {
            handler: function (oldVal, newVal) {
                this.save();
            },
            deep: true,
        }
    },
    methods: {
        save: function () {
            if (this.log_settings.length <= 0) {
                return;
            }
            this.$root.send_post_request(API_CALL_URL + 'log_settings', {settings: JSON.stringify(this.log_settings)}, function (data) {
                if (data.status == 'success') {
                    //Show alert
                }
            });
        },
        fetch_data: function () {
            var self = this;
            self.$root.send_post_request(API_CALL_URL + 'get_log_settings', {}, function (data) {
                if (data.status === 'success') {
                    self.log_settings = JSON.parse(data.log_settings);
                }
            });
        }
    }
});

Vue.component('sp-remove-balance', {
    template: '<a href="javascript:;" @click="remove_balance" class="btn btn-space btn-primary btn-lg" >Cписать баланс</a>\n',
    data: function () {
        return {
            id: null,
            href: window.location.href,
        };
    },
    created: function () {
        this.id = this.href.substring(this.href.lastIndexOf('/') + 1);
    },

    methods: {
        remove_balance: function () {
            var self = this;
            Swal.fire({
                title: 'Are you sure?',
                text: "All balance will be removed and write in withdraw",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then(function (result) {
                if (result.value) {
                    self.$root.send_post_request('/admin/account/edit/' + self.id, {type: 'balance'}, function (data) {
                        if (data.status === STATUS_SUCCESS) {
                            self.$root.show_notification('', data.message);
                            return true;
                        } else {
                            self.$root.show_notification('', 'Something goes wrong', 'danger');
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.close();
                }
            });
        },
    },
});
//var dshboard = new Vue({
//        el: '#app2'
//    });
    
    
var admin = new Vue({
    el: '#app233',
    data: {
        message: {
            success: '',
            error: '',
            tab: ''
        },
        view: '',
        payment_sys: {},
        bot: ''
    },

    created: function () {

        if (location.pathname == '/admin/errors' && location.hash.split('#').length > 1) {
            this.view = location.hash.split('#')[1];
        }
    },

    methods: {
        show_notification: function (title, message, type) {
            var type = type || 'success';

            var options = {
                title: title,
                message: message,
            };

            var settings = {
                type: 'success',
                delay: 1000,
                timer: 1000,
                allow_dismiss: false,
                offset: {
                    y: 20,
                    x: 0
                },
                placement: {
                    from: "bottom",
                    align: "right"
                },
                newest_on_top: true,
            };
            settings.type = type;

            $.notify(options, settings);
        },
        send_get_request: function (url, data, cb) {
            $.get(url, data, cb);
        },
        send_post_request: function (url, data, cb) {
            $.post(url, data, cb);
        }
    }

});



window.onload = function () {
    console.log('INIT VUE');
    const app = new Vue({
        el: '#app2',
        methods: {
            show_notification: function (title, message, type) {
                var type = type || 'success';

                var options = {
                    title: title,
                    message: message,
                };

                var settings = {
                    type: 'success',
                    delay: 1000,
                    timer: 1000,
                    allow_dismiss: false,
                    offset: {
                        y: 20,
                        x: 0
                    },
                    placement: {
                        from: "bottom",
                        align: "right"
                    },
                    newest_on_top: true,
                };
                settings.type = type;

                $.notify(options, settings);
            },
            send_get_request: function (url, data, cb) {
                $.get(url, data, cb);
            },
            send_post_request: function (url, data, cb) {
                $.post(url, data, cb);
            }
        }
    });

};