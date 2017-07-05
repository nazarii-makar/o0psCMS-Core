<?php

namespace o0psCore\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Exception\RuntimeException;
use Zend\View\Model\ViewModel;

/**
 * Class AdminController
 * @package o0psCore\Controller
 */
class AdminController extends AbstractActionController
{
    /**
     * @var \o0psCore\Options\ModuleOptions
     */
    protected $options;

    /**
     * @var \Zend\Mvc\I18n\Translator
     */
    protected $translatorHelper;

    /**
     * @var $viewHelperManager
     */
    protected $viewHelperManager;

    /**
     * @var $analyticMapper
     */
    protected $analyticMapper;

    /**
     * @var \Zend\View\Helper\HeadScript
     */
    protected $headScript;

    /**
     * @var \Zend\View\Helper\InlineScript
     */
    protected $inlineScript;

    /**
     * @var \Zend\View\Helper\HeadLink
     */
    protected $headLink;

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $this->getHeadLink()
             ->appendStylesheet('/assets/lib/jquery.vectormap/jquery-jvectormap-1.2.2.css')
             ->appendStylesheet('/assets/lib/jquery.gritter/css/jquery.gritter.css');

        $this->getInlineScript()
             ->appendFile('/assets/lib/jquery-flot/jquery.flot.js')
             ->appendFile('/assets/lib/jquery-flot/jquery.flot.pie.js')
             ->appendFile('/assets/lib/jquery-flot/jquery.flot.resize.js')
             ->appendFile('/assets/lib/jquery-flot/jquery.flot.tooltip.js')
             ->appendFile('/assets/lib/jquery-flot/plugins/jquery.flot.orderBars.js')
             ->appendFile('/assets/lib/jquery-flot/plugins/curvedLines.js')
             ->appendFile('/assets/lib/jquery.sparkline/jquery.sparkline.min.js')
             ->appendFile('/assets/lib/jquery-ui/jquery-ui.min.js')
             ->appendFile('/assets/lib/jquery.vectormap/jquery-jvectormap-1.2.2.min.js')
             ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js')
             ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js')
             ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-uk-mill-en.js')
             ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-fr-merc-en.js')
             ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-us-il-chicago-mill-en.js')
             ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-au-mill-en.js')
             ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-in-mill-en.js')
             ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-map.js')
             ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-ca-lcc-en.js')
             ->appendFile('/assets/lib/chartjs/Chart.min.js')
             ->appendFile('/assets/lib/jquery.gritter/js/jquery.gritter.js');

        $analyticMapper = $this->getAnalyticMapper();

        $browser     = json_encode($analyticMapper->findBrowser());
        $platform    = json_encode($analyticMapper->findPlatform());
        $city        = json_encode($analyticMapper->findCity());
        $pageViews   = $analyticMapper->findPageViews();
        $pageVisits  = $analyticMapper->findPageVisits();
        $globalStats = $analyticMapper->findGlobalStats();

        $months = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ];

        $pageViewsData = ['data' => [], 'months' => [], 'values' => []];
        if (sizeof($pageViews) > 0) {
            array_map(function ($input) use (&$pageViewsData, &$months) {
                $pageViewsData['data'][$months[intval($input['label'])]] = intval($input['data']);
            }, $pageViews);
        }
        $pageViewsData['months'] = json_encode(array_keys($pageViewsData['data']));
        $pageViewsData['values'] = json_encode(array_values($pageViewsData['data']));

        $pageVisitsData = ['data' => [], 'values' => []];
        if (sizeof($pageVisits) > 0) {
            foreach ($pageVisits as $index => $pageVisit) {
                array_push($pageVisitsData['data'], [$index + 1, intval($pageVisit['data'])]);
            }
        }
        $pageVisitsData['values'] = json_encode($pageVisitsData['data']);

        $globalStatsData = ['data' => [], 'values' => []];
        if (sizeof($globalStats) > 0) {
            foreach ($globalStats as $globalStat) {
                $latLng = null;

                if (!preg_match('/([0-9]+\.[0-9]+),([0-9]+\.[0-9]+)/', $globalStat['loc'], $latLng)) {
                    continue;
                }

                unset($latLng[0]);

                array_push($globalStatsData['data'], [
                    'latLng' => array_values($latLng),
                    'name'   => sprintf('%d Visits', intval($globalStat['data'])),
                    'style'  => [
                        'fill'         => '#F07878',
                        'stroke'       => 'rgba(255,255,255,0.7)',
                        'stroke-width' => 3,
                    ],
                ]);
            }
        }
        $globalStatsData['values'] = json_encode($globalStatsData['data']);

        $this->getInlineScript()->captureStart();

        echo <<<JS
            $(document).ready(function () {
                function widget_top_1() {

                    var data = {$browser};

                    var color1 = tinycolor(App.color.primary).lighten(5).toString();
                    var color2 = App.color.alt2;
                    var color3 = App.color.alt1;

                    var legendContainer = $("#widget-top-1").parent().next().find(".legend");

                    $.plot('#widget-top-1', data, {
                        series: {
                            pie: {
                                show: true,
                                highlight: {
                                    opacity: 0.1
                                }
                            }
                        },
                        grid: {
                            hoverable: true
                        },
                        legend: {
                            container: legendContainer
                        },
                        tooltip: {
                            show: true,
                            content: "%s: %n",
                            shifts: {
                              x: 20,
                              y: 0
                            },
                            defaultTheme: false
                        },
                        colors: [color1, color2, color3]
                    });
                }

                function widget_top_2() {

                    var data = {$platform};

                    var color1 = App.color.alt2;
                    var color2 = App.color.alt4;
                    var color3 = App.color.alt3;
                    var color4 = App.color.alt1;
                    var color5 = tinycolor(App.color.primary).lighten(5).toString();

                    var legendContainer = $("#widget-top-2").parent().next().find(".legend");

                    $.plot('#widget-top-2', data, {
                        series: {
                            pie: {
                                innerRadius: 0.5,
                                show: true,
                                highlight: {
                                    opacity: 0.1
                                }
                            }
                        },
                        grid: {
                            hoverable: true
                        },
                        legend: {
                            container: legendContainer
                        },
                        tooltip: {
                            show: true,
                            content: "%s: %n",
                            shifts: {
                              x: 20,
                              y: 0
                            },
                            defaultTheme: false
                        },
                        colors: [color1, color2, color3, color4, color5]
                    });
                }

                function widget_top_3() {

                    var data = {$city};

                    var color1 = App.color.alt3;
                    var color2 = tinycolor(App.color.alt4).lighten(6.5).toString();

                    var legendContainer = $("#widget-top-3").parent().next().find(".legend");

                    $.plot('#widget-top-3', data, {
                        series: {
                            pie: {
                                show: true,
                                label: {
                                    show: false
                                },
                                highlight: {
                                    opacity: 0.1
                                }
                            }
                        },
                        grid: {
                            hoverable: true
                        },
                        tooltip: {
                            show: true,
                            content: "%s: %n",
                            shifts: {
                              x: 20,
                              y: 0
                            },
                            defaultTheme: false
                        },
                        legend: {
                            container: legendContainer
                        },
                        colors: [color1, color2]
                    });
                }

                function calendar_widget() {
                    var widget = $(".widget-calendar");
                    var calNotes = $(".cal-notes", widget);
                    var calNotesDay = $(".day", calNotes);
                    var calNotesDate = $(".date", calNotes);

                    //Calculate the weekday name
                    var d = new Date();
                    var weekday = new Array(7);
                    weekday[0] = "Sunday";
                    weekday[1] = "Monday";
                    weekday[2] = "Tuesday";
                    weekday[3] = "Wednesday";
                    weekday[4] = "Thursday";
                    weekday[5] = "Friday";
                    weekday[6] = "Saturday";

                    var weekdayName = weekday[d.getDay()];

                    calNotesDay.html(weekdayName);

                    //Calculate the month name
                    var month = new Array();
                    month[0] = "January";
                    month[1] = "February";
                    month[2] = "March";
                    month[3] = "April";
                    month[4] = "May";
                    month[5] = "June";
                    month[6] = "July";
                    month[7] = "August";
                    month[8] = "September";
                    month[9] = "October";
                    month[10] = "November";
                    month[11] = "December";

                    var monthName = month[d.getMonth()];
                    var monthDay = d.getDate();

                    calNotesDate.html(monthName + " " + monthDay);

                    if (typeof jQuery.ui != 'undefined') {
                        $(".ui-datepicker").datepicker({
                            onSelect: function (s, o) {
                                var sd = new Date(s);
                                var weekdayName = weekday[sd.getDay()];
                                var monthName = month[sd.getMonth()];
                                var monthDay = sd.getDate();

                                calNotesDay.html(weekdayName);
                                calNotesDate.html(monthName + " " + monthDay);
                            }
                        });
                    }
                }

                function line_chart1() {

                    var chartEl = $("#line-chart1");
                    var data = {$pageVisitsData['values']};

                    var color1 = App.color.alt3;

                    var plot_statistics = $.plot("#line-chart1",
                        [{
                            data: data,
                            showLabels: true,
                            label: "New Visitors",
                            labelPlacement: "below",
                            canvasRender: true,
                            cColor: "#FFFFFF"
                        }
                        ], {
                            series: {
                                lines: {
                                    show: true,
                                    lineWidth: 2,
                                    fill: true,
                                    fillColor: {colors: [{opacity: 0.6}, {opacity: 0.6}]}
                                },
                                fillColor: "rgba(0, 0, 0, 1)",
                                points: {
                                    show: true,
                                    fill: true,
                                    fillColor: color1
                                },
                                shadowSize: 0
                            },
                            legend: {
                                show: false
                            },
                            grid: {
                                show: true,
                                margin: {
                                    left: -8,
                                    right: -8,
                                    top: 0,
                                    botttom: 0
                                },
                                labelMargin: 0,
                                axisMargin: 0,
                                hoverable: true,
                                clickable: true,
                                tickColor: "rgba(0, 0, 0, 0)",
                                borderWidth: 0
                            },
                            tooltip: {
                                show: true,
                                content: "%y",
                                shifts: {
                                  x: 20,
                                  y: 0
                                },
                                defaultTheme: false
                            },
                            colors: [color1, "#1fb594"],
                            xaxis: {
                                autoscaleMargin: 0,
                                ticks: 11,
                                tickDecimals: 0
                            },
                            yaxis: {
                                autoscaleMargin: 0.5,
                                ticks: 5,
                                tickDecimals: 0
                            }
                        });
                }

                function world_map() {

                    var color = App.color.alt1;

                    $('#world-map').vectorMap({
                        map: 'world_mill_en',
                        backgroundColor: 'transparent',
                        regionStyle: {
                            initial: {
                                fill: color,
                            },
                            hover: {
                                "fill-opacity": 0.8
                            }
                        },
                        markerStyle: {
                            initial: {
                                r: 10
                            },
                            hover: {
                                r: 12,
                                stroke: 'rgba(255,255,255,0.8)',
                                "stroke-width": 4
                            }
                        },
                        markers: {$globalStatsData['values']}
                    });
                }

                function radar_chart() {

                    var color1 = tinycolor(App.color.primary).lighten(6);

                    var data = {
                        labels: {$pageViewsData['months']},
                        datasets: [
                            {
                                label: "Page views",
                                fillColor: color1.setAlpha(.5).toString(),
                                pointColor: color1.setAlpha(.8).toString(),
                                strokeColor: color1.setAlpha(.8).toString(),
                                highlightFill: color1.setAlpha(.75).toString(),
                                highlightStroke: color1.toString(),
                                data: {$pageViewsData['values']}
                            }
                        ]
                    };

                    var radarChart = new Chart($("#radar-chart1").get(0).getContext("2d")).Radar(data, {
                        scaleShowLine: true,
                        responsive: true,
                        maintainAspectRatio: false,
                        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
                    });
                }

                widget_top_1();
                widget_top_2();
                widget_top_3();

                var spk1_color = App.color.alt2;
                var spk2_color = tinycolor(App.color.primary).lighten(5).toString();
                $("#spk1").sparkline([2, 4, 3, 6, 7, 5, 8, 9, 4, 2, 10,], {
                    type: 'bar',
                    width: '80px',
                    height: '30px',
                    barColor: spk1_color
                });
                $("#spk2").sparkline([5, 3, 5, 6, 5, 7, 4, 8, 6, 9, 8,], {
                    type: 'bar',
                    width: '80px',
                    height: '30px',
                    barColor: spk2_color
                });

                calendar_widget();
                line_chart1();

                world_map();
                radar_chart();
            });
JS;

        $this->getInlineScript()->captureEnd();

        $viewModel = new ViewModel();
        $viewModel->setTemplate('o0ps-core/admin/index');

        return $viewModel;
    }

    /**
     * @param $options
     *
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * get options
     *
     * @return \o0psCore\Options\ModuleOptions
     */
    protected function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $translatorHelper
     *
     * @return $this
     */
    public function setTranslatorHelper($translatorHelper)
    {
        $this->translatorHelper = $translatorHelper;

        return $this;

    }

    /**
     * get translatorHelper
     *
     * @return  \Zend\Mvc\I18n\Translator
     */
    protected function getTranslatorHelper()
    {
        return $this->translatorHelper;
    }

    /**
     * @param $analyticMapper
     *
     * @return $this
     */
    public function setAnalyticMapper($analyticMapper)
    {
        $this->analyticMapper = $analyticMapper;

        return $this;

    }

    /**
     * get analyticMapper
     *
     * @return  \o0psCore\Mapper\Analytic
     */
    protected function getAnalyticMapper()
    {
        return $this->analyticMapper;
    }

    /**
     * @param $viewHelperManager
     *
     * @return $this
     */
    public function setViewHelperManager($viewHelperManager)
    {
        $this->viewHelperManager = $viewHelperManager;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getViewHelperManager()
    {
        if (null === $this->viewHelperManager) {
            throw new RuntimeException('No ViewHelperManager instance provided');
        }

        return $this->viewHelperManager;
    }

    /**
     * @return \Zend\View\Helper\HeadScript
     */
    protected function getHeadScript()
    {
        if (null === $this->headScript) {
            $this->headScript = $this->getViewHelperManager()->get('HeadScript');
        }

        return $this->headScript;
    }

    /**
     * @return \Zend\View\Helper\InlineScript
     */
    protected function getInlineScript()
    {
        if (null === $this->inlineScript) {
            $this->inlineScript = $this->getViewHelperManager()->get('InlineScript');
        }

        return $this->inlineScript;
    }

    /**
     * @return \Zend\View\Helper\HeadLink
     */
    protected function getHeadLink()
    {
        if (null === $this->headLink) {
            $this->headLink = $this->getViewHelperManager()->get('HeadLink');
        }

        return $this->headLink;
    }
}