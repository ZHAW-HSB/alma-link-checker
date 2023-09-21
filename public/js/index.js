/*
 * This file is part of the Link Checker package.
 *
 * (c) ZHAW HSB <apps.hsb@zhaw.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Startup...
 */
(function ($) {
    $(document).ready(function () {
        loadCollectionDatatable();
        loadPortfolioDatatable();
    });

    $(window).on('load', function () {
        $('#loader').hide();
    });

})(jQuery);


/**
 * Handle Main Navigation
 */
(function ($) {
    $(document).ready(function () {
        let language = $('html').attr('lang');
        let mainNavigationItems = $('#main-navigation a');
        let ePortfolioTab = $('#e-portfolio-tab');
        let eCollectionTab = $('#e-collection-tab');

        if (mainNavigationItems.length == 0) {
            return;
        }

        if (language) {
            language = '&lang=' + language;
        }

        mainNavigationItems.on('click', function (e) {
            e.preventDefault();
            ePortfolioTab.addClass('d-none');
            eCollectionTab.addClass('d-none');
            mainNavigationItems.parent().removeClass('active');

            $(this).parent().addClass('active');
            $('#' + $(this).data('href')).removeClass('d-none');
            history.pushState(null, '', '?tab=' + $(this).data('url') + language);
        });
    });
})(jQuery);

/**
 * Update collection url
 */
(function ($) {
    $(document).ready(function () {
        let alertMessageContainer = $('#alert-message-container');

        $(document).on('click', '.collection-update-button', function (e) {
            e.preventDefault();
            let element = $(e.target);
            let collectionId = element.data('collection-id');
            let publicName = element.data('public-name');
            let type = element.data('type');
            let newUrl = element.prev().val();
            let isUrlOverride = element.data('is-url-override');

            alertMessageContainer.addClass('d-none');
            alertMessageContainer.removeClass('alert-success alert-danger');

            $.ajax({
                type: "GET",
                url: window.location.hostname,
                data: {
                    collectionId: collectionId,
                    publicName: publicName,
                    type: type,
                    newUrl: newUrl,
                    isUrlOverride: isUrlOverride
                },
                beforeSend: function () {
                    $('#loader').show();
                }
            }).done(function (result) {
                $('#loader').hide();

                let res = JSON.parse(result);

                alertMessageContainer.html(res.message);
                alertMessageContainer.removeClass('d-none');

                if (res.code == 400) {
                    alertMessageContainer.addClass('alert-danger');
                }

                if (res.code == 200) {
                    element.parent().parent().fadeOut(2500);
                    alertMessageContainer.addClass('alert-success');
                }

                setTimeout(() => {
                    alertMessageContainer.addClass('d-none');

                    if ($('table').children().length == 0) {
                        $('#collection-clear-cache').click();
                    }
                }, 2500);


            }).fail(function (error) {
                $('#loader').hide();
                console.log('An unexpected error occured');
            });
        });
    });
})(jQuery);

/**
 * Load Collection data
 */
(function ($) {
    $(document).ready(function () {
        let collectionCheckLinkButton = $('#collection-check-links');
        let container = $('#e-collection-record-list');

        if (collectionCheckLinkButton.length == 0) {
            return;
        }

        collectionCheckLinkButton.on('click', function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: window.location.hostname,
                data: {
                    checkCollectionLinks: "process"
                },
                beforeSend: function () {
                    $('#loader').show();
                }
            }).done(function (res) {
                $('#loader').hide();
                container.html(res);

                setTimeout(() => {
                    loadCollectionDatatable();
                }, 0);

            }).fail(function (error) {
                $('#loader').hide();
                console.log('An unexpected error occured');
            });
        });
    });
})(jQuery);

/**
 * Clear collection cache
 */
(function ($) {
    $(document).ready(function () {
        let collectionClearCacheButton = $('#collection-clear-cache');
        let container = $('#e-collection-record-list');

        if (collectionClearCacheButton.length == 0) {
            return;
        }

        collectionClearCacheButton.on('click', function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: window.location.hostname,
                data: {
                    clearCollectionCache: true
                },
                beforeSend: function () {
                    $('#loader').show();
                }
            }).done(function (result) {
                $('#loader').hide();
                let res = JSON.parse(result);
                container.html(res.message);

            }).fail(function (error) {
                $('#loader').hide();
                console.log('An unexpected error occured');
            });
        });
    });
})(jQuery);


/**
 * Update portfolio url
 */
(function ($) {
    $(document).ready(function () {
        let alertMessageContainer = $('#alert-message-container');

        $(document).on('click', '.portfolio-update-button', function (e) {
            e.preventDefault();
            let element = $(e.target);
            let collectionId = element.data('collection-id');
            let serviceId = element.data('service-id');
            let portfolioId = element.data('portfolio-id');
            let newUrl = element.prev().val();
            let isUrlOverride = element.data('is-url-override');

            alertMessageContainer.addClass('d-none');
            alertMessageContainer.removeClass('alert-success alert-danger');

            $.ajax({
                type: "GET",
                url: window.location.hostname,
                data: {
                    collectionId: collectionId,
                    serviceId: serviceId,
                    portfolioId: portfolioId,
                    newUrl: newUrl,
                    isUrlOverride: isUrlOverride
                },
                beforeSend: function () {
                    $('#loader').show();
                }
            }).done(function (result) {
                $('#loader').hide();

                let res = JSON.parse(result);

                alertMessageContainer.html(res.message);
                alertMessageContainer.removeClass('d-none');

                if (res.code == 400) {
                    alertMessageContainer.addClass('alert-danger');
                }

                if (res.code == 200) {
                    element.parent().parent().fadeOut(2500);
                    alertMessageContainer.addClass('alert-success');
                }

                setTimeout(() => {
                    alertMessageContainer.addClass('d-none');

                    if ($('table').children().length == 0) {
                        $('#portfolio-clear-cache').click();
                    }

                }, 2500);


            }).fail(function (error) {
                $('#loader').hide();
                console.log('An unexpected error occured');
            });
        });
    });
})(jQuery);

/**
 * Load portfolio data
 */
(function ($) {
    $(document).ready(function () {
        let portfolioCheckLinkButton = $('#portfolio-check-links');
        let container = $('#e-portfolio-record-list');

        if (portfolioCheckLinkButton.length == 0) {
            return;
        }

        portfolioCheckLinkButton.on('click', function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: window.location.hostname,
                data: {
                    checkPortfolioLinks: "process"
                },
                beforeSend: function () {
                    $('#loader').show();
                }
            }).done(function (res) {
                $('#loader').hide();
                container.html(res);

                setTimeout(() => {
                    loadPortfolioDatatable();
                }, 0);

            }).fail(function (error) {
                $('#loader').hide();
                console.log('An unexpected error occured');
            });
        });
    });
})(jQuery);

/**
 * Clear portfolio cache
 */
(function ($) {
    $(document).ready(function () {
        let portfolioClearCacheButton = $('#portfolio-clear-cache');
        let container = $('#e-portfolio-record-list');

        if (portfolioClearCacheButton.length == 0) {
            return;
        }

        portfolioClearCacheButton.on('click', function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: window.location.hostname,
                data: {
                    clearPortfolioCache: true
                },
                beforeSend: function () {
                    $('#loader').show();
                }
            }).done(function (result) {
                $('#loader').hide();
                let res = JSON.parse(result);
                container.html(res.message);

            }).fail(function (error) {
                $('#loader').hide();
                console.log('An unexpected error occured');
            });
        });
    });
})(jQuery);

/**
 * Initialize collection datatable
 */
function loadCollectionDatatable() {
    if ($('#collection-datatable').length > 0) {
        $('#collection-datatable').DataTable({
            pageLength: 1000,
            searching: false,
            ordering: false,
            responsive: true,
            dom: 'Bfrtip',
            fixedHeader: {
                header: false,
                footer: true
            },
            buttons: [
                {
                    title: 'Link Checker - Collections',
                    extend: 'excelHtml5',
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5],
                    },
                    customize: function (xlsx) {
                        let sheet = xlsx.xl.worksheets['sheet1.xml'];
                        let columnTextNodes = sheet.querySelectorAll('c[r^="E"] t');

                        columnTextNodes.forEach((element) => {
                            element.textContent = element.textContent.split('.').filter((e) => {
                                return e;
                            }).join(', ');

                            element.textContent = element.textContent.replaceAll("  ", "");
                        });
                    }
                }
            ],
            language: {
                emptyTable: "-",
                paginate: {
                    first: "<<",
                    previous: "<",
                    next: ">",
                    last: ">>"
                },
            }
        });
    }
}

/**
 * Initialize portfolio datatable
 */
function loadPortfolioDatatable() {
    if ($('#portfolio-datatable').length > 0) {
        $('#portfolio-datatable').DataTable({
            pageLength: 1000,
            searching: false,
            ordering: false,
            responsive: true,
            dom: 'Bfrtip',
            fixedHeader: {
                header: false,
                footer: true
            },
            buttons: [
                {
                    title: 'Link Checker - Portfolios',
                    extend: 'excelHtml5',
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6],
                    },
                    customize: function (xlsx) {
                        let sheet = xlsx.xl.worksheets['sheet1.xml'];
                        let columnTextNodes = sheet.querySelectorAll('c[r^="E"] t');

                        columnTextNodes.forEach((element) => {
                            element.textContent = element.textContent.split('.').filter((e) => {
                                return e;
                            }).join(', ');

                            element.textContent = element.textContent.replaceAll("  ", "");
                        });
                    }
                }
            ],
            language: {
                emptyTable: "-",
                paginate: {
                    first: "<<",
                    previous: "<",
                    next: ">",
                    last: ">>"
                },
            }
        });
    }
}
