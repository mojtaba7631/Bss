$(function () {
    "use strict";

    $('.sparkbar').sparkline('html', {type: 'bar'});

    $('.sparkline-pie').sparkline('html', {
        type: 'pie',
        offset: 90,
        width: '100px',
        height: '100px',
        sliceColors: ['#29bd73', '#182973', '#ffcd55']
    });

    // notification popup
    // toastr.options.closeButton = true;
    // toastr.options.positionClass = 'toast-bottom-right';
    // toastr.options.showDuration = 1000;
    // toastr['info']('سلام به داشبورد مدیریت پروژه ی نما خوش آمدید');


    $('.knob').knob({
        draw: function () {
        }
    });
    $(".rtl .knob").knob({
        draw: function () {
            //style rtl
            this.i.css({
                'margin-right': '-' + ((this.w * 3 / 4 + 2) >> 0) + 'px',
                'margin-left': 'auto'
            });
        },
    });

});

$(function () {
    "use strict";
    initSparkline();

    var values2 = getRandomValues();
    var paramsBar = {
        type: 'bar',
        barWidth: 10,
        height: 80,
        barSpacing: 8,
        barColor: '#ffffff'
    };
    $('#minibar-chart1').sparkline(values2[0], paramsBar);
    $('#minibar-chart2').sparkline(values2[1], paramsBar);
    $('#minibar-chart3').sparkline(values2[2], paramsBar);
    $('#minibar-chart4').sparkline(values2[3], paramsBar);

    function getRandomValues() {
        // data setup
        var values = new Array(20);

        for (var i = 0; i < values.length; i++) {
            values[i] = [5 + randomVal(), 10 + randomVal(), 15 + randomVal(), 20 + randomVal(), 30 + randomVal(),
                35 + randomVal(), 40 + randomVal(), 45 + randomVal(), 50 + randomVal()
            ];
        }

        return values;
    }

    function randomVal() {
        return Math.floor(Math.random() * 80);
    }
});

// C3 Chart js
$(function () {
    "use strict";
    // Small chart widgets
    var chart = c3.generate({
        bindto: '#chart-bg-users-1',
        padding: {
            bottom: -10,
            left: -1,
            right: -1
        },
        data: {
            names: {
                data1: 'کاربران انلاین'
            },
            columns: [
                ['داده1', 30, 40, 10, 40, 12, 22, 40]
            ],
            type: 'area'
        },
        legend: {
            show: false
        },
        transition: {
            duration: 0
        },
        point: {
            show: false
        },
        tooltip: {
            format: {
                title: function (x) {
                    return '';
                }
            }
        },
        axis: {
            y: {
                padding: {
                    bottom: 0,
                },
                show: false,
                tick: {
                    outer: false
                }
            },
            x: {
                padding: {
                    left: 0,
                    right: 0
                },
                show: false
            }
        },
        color: {
            pattern: ['#467fcf']
        }
    });
    var chart = c3.generate({
        bindto: '#chart-bg-users-2',
        padding: {
            bottom: -10,
            left: -1,
            right: -1
        },
        data: {
            names: {
                data1: 'کاربران انلاین'
            },
            columns: [
                ['داده1', 30, 40, 10, 40, 12, 22, 40]
            ],
            type: 'area'
        },
        legend: {
            show: false
        },
        transition: {
            duration: 0
        },
        point: {
            show: false
        },
        tooltip: {
            format: {
                title: function (x) {
                    return '';
                }
            }
        },
        axis: {
            y: {
                padding: {
                    bottom: 0,
                },
                show: false,
                tick: {
                    outer: false
                }
            },
            x: {
                padding: {
                    left: 0,
                    right: 0
                },
                show: false
            }
        },
        color: {
            pattern: ['#e74c3c']
        }
    });
    var chart = c3.generate({
        bindto: '#chart-bg-users-3',
        padding: {
            bottom: -10,
            left: -1,
            right: -1
        },
        data: {
            names: {
                data1: 'کاربران انلاین'
            },
            columns: [
                ['داده1', 30, 40, 10, 40, 12, 22, 40]
            ],
            type: 'area'
        },
        legend: {
            show: false
        },
        transition: {
            duration: 0
        },
        point: {
            show: false
        },
        tooltip: {
            format: {
                title: function (x) {
                    return '';
                }
            }
        },
        axis: {
            y: {
                padding: {
                    bottom: 0,
                },
                show: false,
                tick: {
                    outer: false
                }
            },
            x: {
                padding: {
                    left: 0,
                    right: 0
                },
                show: false
            }
        },
        color: {
            pattern: ['#5eba00']
        }
    });
    var chart = c3.generate({
        bindto: '#chart-bg-users-4',
        padding: {
            bottom: -10,
            left: -1,
            right: -1
        },
        data: {
            names: {
                data1: 'کاربران انلاین'
            },
            columns: [
                ['داده1', 30, 40, 10, 40, 12, 22, 40]
            ],
            type: 'area'
        },
        legend: {
            show: false
        },
        transition: {
            duration: 0
        },
        point: {
            show: false
        },
        tooltip: {
            format: {
                title: function (x) {
                    return '';
                }
            }
        },
        axis: {
            y: {
                padding: {
                    bottom: 0,
                },
                show: false,
                tick: {
                    outer: false
                }
            },
            x: {
                padding: {
                    left: 0,
                    right: 0
                },
                show: false
            }
        },
        color: {
            pattern: ['#f1c40f']
        }
    });

    // chart-employment
    var chart = c3.generate({
        bindto: '#chart-employment', // id of chart wrapper
        data: {
            columns: [
                // each columns data
                ['داده1', 1, 2, 8, 6, 7, 14, 11],
                ['داده2', 0, 5, 15, 27, 15, 21, 25],
                ['داده3', 2, 17, 18, 21, 8, 30, 29]
            ],
            type: 'line', // default type of chart
            colors: {
                'data1': '#b8428c',
                'data2': '#f19b9c',
                'data3': '#f9cdac',
            },
            names: {
                // name of each serie
                'data1': 'Development',
                'data2': 'Marketing',
                'data3': 'Sales'
            }
        },
        axis: {
            x: {
                type: 'category',
                // name of each category
                categories: ['2012', '2013', '2014', '2015', '2016', '2017', '2018']
            },
        },
        legend: {
            show: true, //hide legend
        },
        padding: {
            bottom: 20,
            top: 0
        },
    });

    // Members
    var chart = c3.generate({
        bindto: '#chart-bar-stacked', // id of chart wrapper
        data: {
            columns: [
                // each columns data
                ['داده1', 11, 8, 15, 18, 19, 17],
                ['داده2', 7, 7, 5, 7, 9, 12]
            ],
            type: 'bar', // default type of chart
            groups: [
                ['داده1', 'داده2']
            ],
            colors: {
                'data1': '#db5087',
                'data2': '#f9cdac',
            },
            names: {
                // name of each serie
                'data1': 'User',
                'data2': 'VIP'
            }
        },
        axis: {
            x: {
                type: 'category',
                // name of each category
                categories: ['ژان', 'فور', 'مار', 'اور', 'مه', 'ژوئن']
            },
        },
        bar: {
            width: 10
        },
        legend: {
            show: false, //hide legend
        },
        padding: {
            bottom: -20,
            top: 0,
            left: -6,
        },
    });
    // Marketing
    var chart = c3.generate({
        bindto: '#chart-area-Marketing', // id of chart wrapper
        data: {
            columns: [
                // each columns data
                ['داده1', 11, 8, 15, 18, 19, 17],
                ['داده2', 7, 7, 5, 7, 9, 12]
            ],
            type: 'area-spline', // default type of chart
            groups: [
                ['داده1', 'داده2']
            ],
            colors: {
                'data1': '#e8608a',
                'data2': '#f3a8a1',
            },
            names: {
                // name of each serie
                'data1': 'ماه گذشته',
                'data2': 'این ماه'
            }
        },
        axis: {
            x: {
                type: 'category',
                // name of each category
                categories: ['ژان', 'فور', 'مار', 'اور', 'مه', 'ژوئن']
            },
        },
        legend: {
            show: false, //hide legend
        },
        padding: {
            bottom: -20,
            top: 0,
            left: -7,
        },
    });

    c3.generate({
        bindto: '#chart-pie', // id of chart wrapper
        data: {
            columns: [
                // each columns data
                ['داده1', 63],
                ['داده2', 44],
                ['داده3', 12],
                ['داده4', 14]
            ],
            type: 'pie', // default type of chart
            colors: {
                'data1': '#973490',
                'data2': '#db5087',
                'data3': '#ed8495',
                'data4': '#f9cdac',
            },
            names: {
                // name of each serie
                'data1': 'اپل',
                'data2': 'نوکیا',
                'data3': 'سونی',
                'data4': 'ویوو',
            }
        },
        axis: {},
        legend: {
            show: true, //hide legend
        },
        padding: {
            bottom: 20,
            top: 0
        },
    });
});

$(function () {
    "use strict";
    var dataStackedBar = {
        labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5', 'Q6'],
        series: [
            [8000, 12000, 3600, 1300, 12000, 12000],
            [2000, 4000, 5000, 3000, 7000, 4000],
            [1000, 2000, 4000, 6000, 3000, 2000]
        ]
    };
    new Chartist.Bar('#stackedbar-chart', dataStackedBar, {
        height: "228px",
        stackBars: true,
        axisX: {
            showGrid: false
        },
        axisY: {
            labelInterpolationFnc: function (value) {
                return (value / 1000) + 'k';
            }
        },
        plugins: [
            Chartist.plugins.tooltip({
                appendToBody: true
            }),
            Chartist.plugins.legend({
                legendNames: ['درامد', 'درآمد', 'هزینه']
            })
        ]
    }).on('draw', function (data) {
        if (data.type === 'bar') {
            data.element.attr({
                style: 'stroke-width: 25px'
            });
        }
    });
});
