let viewsByDayChart;

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

/**
 * first card: route views chart
 */
function setViewsChart(type, data) {
    var chart = document.getElementById('viewsByDayChart');
    var labels = data.dates;
    var views = data.views;
    var users = data.users;

    if (type === 'new') {
        viewsByDayChart = echarts.init(chart);
        $('#firstCardH6').hide();
    }

    viewsByDayChart.setOption({
        grid: {
            top: '6',
            right: '0',
            bottom: '17',
            left: '25',
        },
        xAxis: {
            data: labels,
            axisLine: {
                lineStyle: {
                    color: '#576574'
                }
            },
            axisLabel: {
                fontSize: 10,
                color: '#576574'
            }
        },
        tooltip: {
            show: true,
            showContent: true,
            alwaysShowContent: false,
            triggerOn: 'mousemove',
            trigger: 'axis',
            axisPointer:
                {
                    label: {
                        show: false,
                    },
                }
        },
        yAxis: {
            splitLine: {
                lineStyle: {
                    color: 'none'
                }
            },
            axisLine: {
                lineStyle: {
                    color: '#576574'
                }
            },
            axisLabel: {
                fontSize: 10,
                color: '#576574'
            }
        },
        series: [
            {
                name: 'بازدید',
                type: 'line',
                smooth: true,
                symbolSize: 10,
                size: 10,
                data: views,
                color: ['#00d2d3']
            },
            {
                name: 'کاربر',
                type: 'line',
                smooth: true,
                symbolSize: 10,
                size: 10,
                data: users,
                color: ['#ff9f43']
            },
        ],
    });
}
function viewsChart(type, period) {
    let id = $(this).attr('id');
    $('#'+id).addClass('btn-progress').addClass('disabled');
    $('#firstCardH6').show();
    $.get(BASE_URL+"/statistics/views-charts/views/"+period, function( data, status ) {
        if (data.status === 200) {
            $('#'+id).removeClass('btn-progress').removeClass('disabled')
            setViewsChart(type, data);
            $('#firstCardH6').hide();
        }
    });
}

/**
 * second card: route views table
 */
function routeViewsTable(period) {
    let id = $(this).attr('id')
    $('#'+id).addClass('btn-progress').addClass('disabled')

    $.get(BASE_URL+"/statistics/views-charts/route-views/"+period, function( data, status ) {
        if(data.status === 200) {
            $('#'+id).removeClass('btn-progress').removeClass('disabled')

            let viewsByRouteTable = $("#viewsByRouteTableDiv");
            let rows = `
                    <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>صفحه</th>
                            <th>بازدید</th>
                            <th>کاربر</th>
                        </tr>
                    </thead>
                    <tbody>`;
            for (var i = 0; i < data.route_views.length; i++) {
                let users = data.route_views[i].users === 0 ?
                    `<span data-toggle="tooltip" title="" data-original-title="کاربر وارد نشده!">--</span>` :
                    data.route_views[i].users;
                rows += `
                        <tr>
                            <td><a href="${data.route_views[i].path}"><code>${data.route_views[i].path}</code></a>
                            <td>${data.route_views[i].views}</td>
                            <td>${users}</td>
                        </tr>`;
            }

            rows += `</tbody></table>`;

            $('#secondCardH6').remove()
            viewsByRouteTable.empty().append(rows)
        }
    })
}

/**
 * third card: users platforms pie
 */
function viewersPlatformsPie() {
    $.get(BASE_URL+"/statistics/views-charts/viewer-platforms/", function( data, status ) {
        if(data.status === 200) {
            let chart = document.getElementById('viewersPlatformsPie');
            let barChart = echarts.init(chart);

            barChart.setOption({
                tooltip: {
                    trigger: "item",
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    x: "center",
                    y: "bottom",
                    textStyle: { color: '#9aa0ac' },
                    data: data.viewers_platforms
                },
                calculable: !0,
                series: [{
                    name: "تعداد کاربران",
                    type: "pie",
                    radius: "55%",
                    center: ["50%", "48%"],
                    data: data.viewers_platforms_count
                }],
                color: [
                    '#1abc9c',
                    '#f39c12',
                    '#e74c3c',
                    '#27ae60',
                    '#9b59b6',
                    '#34495e'
                ]
            });

            $('#thirdCardH6').remove()
        }
    });
}

$(window).on('load', function () {
    /**
     * set default data's
     */
    sleep(10).then(viewsChart('new', 'last_seven_days'));
    sleep(10).then(routeViewsTable(null));
    sleep(10).then(viewersPlatformsPie());

    $('.statistics-period li').on('click', function () {
        if ($(this).hasClass('active')) {
            return;
        }

        $(this).closest('.statistics-period').find('li').removeClass('active');
        $(this).addClass('active');

        let chartId = $(this).closest('.tab-pane').find('.chart-area').attr('id');
        prepareChart('#' + chartId);
    });

    $('#statistics-card .persian_date_picker ').on('change', function () {
        let chartId = $(this).closest('.tab-pane').find('.chart-area').attr('id');
        prepareChart('#' + chartId);
    });

    $('#statistics-card a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        let href = $(this).attr('href');

        if ($(href).find('.statistics-period .nav-link.active').length) {
            return;
        }

        let period = $(href).find('.statistics-period').data('default-period');

        $(href)
            .find('[data-period="' + period + '"]')
            .trigger('click');
    });

    $('#statistics-card .nav-tabs li').first().find('a').trigger('click');
});

function prepareChart(chartId) {
    let period = $(chartId).closest('.tab-pane').find('.statistics-period .nav-link.active').data('period');
    let from_date = $(chartId).closest('.tab-pane').find('[name="from_date"]').val();
    let to_date = $(chartId).closest('.tab-pane').find('[name="to_date"]').val();

    $.ajax({
        url: $(chartId).data('action'),
        type: 'GET',
        data: {
            period: period,
            from_date: from_date,
            to_date: to_date
        },
        success: function (data) {
            if (data.status != 'success') {
                return;
            }

            let meta = data.meta;
            data = data.data;

            categories = [];
            let total = [];

            for (const [key, value] of Object.entries(data)) {
                categories.push(value.chart_category);
                total.push(value.total);
            }

            let series = [
                {
                    name: 'بازدید',
                    data: total
                }
            ];

            renderChart(chartId, series, categories);

            switch (chartId) {
                case '#view-counts-chart': {
                    $(chartId).closest('.tab-pane').find('.views-total').text(meta.total);
                    $(chartId).closest('.tab-pane').find('.views-avg').text(meta.avg);
                    break;
                }
                case '#viewer-counts-chart': {
                    $(chartId).closest('.tab-pane').find('.viewers-total').text(meta.total);
                    $(chartId).closest('.tab-pane').find('.viewers-avg').text(meta.avg);
                    break;
                }
            }
        },
        beforeSend: function (xhr) {
            block($(chartId).closest('.tab-pane'));
        },
        complete: function () {
            unblock($(chartId).closest('.tab-pane'));
        }
    });
}

function renderChart(chartId, series, categories) {
    let options = {
        series: series,
        chart: {
            type: 'bar',
            height: 430,
            fontFamily: APP_FONT_FAMILY,
            stacked: true,
            toolbar: {
                show: true
            },
            events: {
                mounted: (chartContext, config) => {
                    setTimeout(() => {
                        addAnnotations(chartContext, config);
                    });
                },
                updated: (chartContext, config) => {
                    setTimeout(() => {
                        addAnnotations(chartContext, config);
                    });
                }
            }
        },
        stroke: {
            with: 1
        },
        responsive: [
            {
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }
        ],
        plotOptions: {
            bar: {
                dataLabels: {position: 'top'},
                horizontal: false,
                borderRadius: 2
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            type: 'category',
            categories: categories
        },
        yaxis: {
            labels: {
                formatter: (value) => {
                    return abbreviateNumber(value);
                }
            }
        },
        colors: ['#008ffb', '#ff4560'],
        legend: {
            position: 'bottom',
            offsetY: 0
        },
        fill: {
            opacity: 1
        }
    };

    if ($(chartId).data('rendered')) {
        $(chartId).data('rendered').destroy();
        $(chartId).css('min-height', $(chartId).data('min-height'));
    }

    let chart = new ApexCharts(document.querySelector(chartId), options);

    chart.render();

    $(chartId).data('rendered', chart);
}

$('.persian_date_picker').customPersianDate();

const addAnnotations = (chart, config) => {
    const seriesTotals = config.globals.stackedSeriesTotals;
    const isHorizontal = config.config.plotOptions.bar.horizontal;
    chart.clearAnnotations();

    try {
        categories.forEach((category, index) => {
            if (seriesTotals[index]) {
                chart.addPointAnnotation(
                    {
                        y: isHorizontal ? calcHorizontalY(config, index, categories) : seriesTotals[index],
                        x: isHorizontal ? 0 : category,
                        label: {
                            text: `${abbreviateNumber(seriesTotals[index])}`,
                            borderWidth: 0,
                            style: {
                                fontWeight: 900,
                                background: 'transparent'
                            }
                        },
                        marker: {
                            size: 0
                        }
                    },
                    false
                );
            }
        });
    } catch (error) {
        console.log(`Add point annotation error: ${error.message}`);
    }
};

const calcHorizontalY = (config, index, categories) => {
    const catLength = categories.length;
    const yRange = config.globals.yRange[0];
    const minY = config.globals.minY;
    const halfBarHeight = yRange / catLength / 2;

    return minY + halfBarHeight + 2 * halfBarHeight * (catLength - 1 - index);
};
