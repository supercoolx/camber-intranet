callboardErrors = '';

(function () {
    /**
     * @class vlForm
     */
    var callboardErrors = {
        source: [],
        grouppedData: [],
        config: {
            
        },
        init: function () {
//                v.setReportList();
//                v.loadStockTable();
//                v.initFormEventListeners();
//                v.initStockTableListeners();
            v.initConstrolButtonListeners();
            v.cacheData();
           //console.log( v.source);
        },
        
        cacheData: function() {
            $(document).find('errorentry').each(function(){
                var entryDate = $(this).find('datetime').text();
//                entryDate = Date.parse(entryDate);
//                entryDate = new Date(entryDate).format('yyyy-mm-dd');
                entryDate = entryDate.split(" ")[0];
                v.source.push({
                    date: entryDate,
                    domain: $(this).find('domain').text(),
                    errorMsg: $(this).find('errormsg').text(),
                    entryHtml: $(this).prop('outerHTML'),
                });
                
            });
        },
        
        initConstrolButtonListeners: function() {
            $(document).on('click', '.default-groupping label', function(){
                //debugger;
                $('.default-groupping label').removeClass('active');
                $('.default-groupping input').prop('checked', false);
                //debugger;
                $(this).addClass('active');
                $(this).find('input').prop('checked', true);
                var groupBy = $(this).find('input').attr('group-by');
                var result = v.groupDataBy(groupBy);
                var resultHtml = '';
                if(groupBy === 'default'){
                    resultHtml = result.join('<br />');
                } else {
                    var headers = Object.keys(result).sort(function(b,a){
                       a = a.split(/[- :]/);
                       b = b.split(/[- :]/);
                       return new Date(a[0], a[1] - 1, a[2],0,0,0) - new Date(b[0], b[1] - 1, b[2],0,0,0);
                   });
//                    debugger;
                    var temp = [];
                    
                    var i = 0;
                    //debugger;
                    headers.forEach(function(header) {
                        i++;
//                        temp.push(`<b>${header}</b>`);
//                        temp.push(result[header].join('<br />'));
                        var collapsed = i === 0? 'in': '';
                        var accordeonEntry = `
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse${i}">
                                ${header}</a>
                              </h4>
                            </div>
                            <div id="collapse${i}" class="panel-collapse collapse ${collapsed}">
                              <div class="panel-body">
                                  ${result[header].join('<br />')}
                              </div>
                            </div>
                        </div>`;
                        temp.push(accordeonEntry);
                        
                    });
                    //resultHtml = temp.join('');
                    
                    
                    var resultHtml = `
                        <div class="panel-group" id="accordion">
                          ${temp.join('')}
                        </div>
                    `;
                }
                
                
                
                $('.all-errors').empty().append(resultHtml);
                
                return false;
            });
        },
        
        groupDataBy: function(param) {
             var result = [];
            if(param !== 'default') {
                result = v.source.reduce(function (r, a) {
                    r[a[param]] = r[a[param]] || [];
                    r[a[param]].push(a.entryHtml);
                    return r;
                }, Object.create(null));

                if(param !== 'date' && param !== 'domain') {
                    Object.keys(result).sort().reduce((r, k) => (r[k] = result[k], r), {});
                } else if(param !== 'errorMsg' && param !== 'domain') {
                    Object.keys(result).sort((a,b) => (a<b)).reduce((r, k) => (r[k] = result[k], r), {});
                }else{
                    Object.keys(result).sort().reduce((r, k) => (r[k] = result[k], r), {});
                }
            } else {
                for(var i=0; i<v.source.length; i++){
                    result.push(v.source[i]['entryHtml']);
                }
            }

//            if(param === 'date') {
//                result.sort(function(a,b){
//                    // Turn your strings into dates, and then subtract them
//                    // to get a value that is either negative, positive, or zero.
//                    return new Date(b.date) - new Date(a.date);
//                });
//            }
            return result;
        },

//        loadStockTable: function () {
//
//        },
        /**
         *
         * @param {array} tableHeaders
         * @returns {String}
         */
//        initStockTableListeners: function() {
////                $(document).on("click", ".stocks-table th", function (e) {
////                        return false;
////                });
//        },
    }

    var v = callboardErrors;
    window.callboardErrors = callboardErrors;
})();
