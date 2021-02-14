$(document).ready(function () {
    $.ajax({
        url: apiUrl + "pr/getDashboardData",
        type: "GET",
        data: { action: '', row: 5 },
        dataType: "json",
        success: function (r) {
            if (r.success) {
                $('#activites').html(activites(r.data.activity));
                $('#recent-paid').html(paidActivites(r.data.paymentActivity));
                barChart($('#barChart'), lastTwelveMonth(), r.data.paid, r.data.unpaid)
                // barChart($('#barChart2'),lastTwelveMonth(),'Unpaid',r.data.unpaid)
                initDonutChart(r.data.donut);
                setData(r.data.count)
            } else {
                showValidationError(r)
            }
        }
    })
})

function activites(aData) {
    let sHtml = '';
    $.each(aData, function (k, v) {
        if (typeof v['loginDiffer'] != 'undefined') {
            sHtml += '<p class="acts">' + v['loginDiffer'] + '</p>'
        }
        if (typeof v['clientDiffer'] != 'undefined') {
            sHtml += '<p class="acts">' + v['clientDiffer'] + '</p>'
        }
        if (typeof v['invoiceDiffer'] != 'undefined') {
            sHtml += '<p class="acts">' + v['invoiceDiffer'] + '</p>';
        }
    })
    return sHtml;
}
function paidActivites(aData) {
    let sHtml = '';
    $.each(aData, function (k, v) {
        if (typeof v['paymentDiffer'] != 'undefined') {
            sHtml += '<p class="acts">' + v['paymentDiffer'] + '</p>';
        }
    })
    return sHtml;
}
function setData(aData) {
    $.each(aData, function (k, v) {
        $('.' + k).text(v)
    })
}
function initDonutChart(aData) {
    $.each(aData, function (k, v) {
        if (k == 'paid') {
            $('.total-paid').text(v + ' PAID')
        }
        $('#' + k).text(k + ': ' + v)
    })
    var total = aData.total;
    var oData = [
        { label: "Draft", value: aData.draft },
        { label: "Sent", value: aData.sent },
        { label: "Paid", value: aData.paid },
        { label: "Unpaid", value: aData.unpaid },
    ];
    Morris.Donut({
        element: 'donut_chart',
        data: oData,
        colors: ['#007399', '#595959', '#009900', 'rgb(0, 150, 136)'],//, 'rgb(96, 125, 139)'],
        formatter: function (value, data) {
            return Math.floor(value / total * 100) + '%';
        },
        resize: true
    });
}
function barChart(target, labels, oPaid, oUnPaid) {
    // target.canvas.parentNode.style.height = '128px';
    new Chart(target, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: '# of Paid',
                backgroundColor: "#499484",
                data: oPaid,
            }, {
                label: '# of Unpaid',
                backgroundColor: "#0ed3bb",
                data: oUnPaid,
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}
function lastTwelveMonth() {
    var monthName = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    var d = new Date();
    d.setDate(1);
    var aMonth = [];
    for (i = 0; i <= 11; i++) {
        aMonth[i] = monthName[d.getMonth()] + ' ' + d.getFullYear();
        d.setMonth(d.getMonth() - 1);
    }
    return aMonth.reverse();
}
$(document).on('click', '.more-act', function () {
    let self = $(this);
    let data = self.attr('data');
    $.ajax({
        url: apiUrl + "pr/getDashboardData",
        type: "GET",
        data: { action: data, row: 20 },
        dataType: "json",
        success: function (r) {
            if (r.success) {
                $('.modal-title').html(self.text());
                if (data == 'activites') {
                    $('.modal-body').html(activites(r.data.activity));
                } else if (data == 'paid') {
                    $('.modal-body').html(paidActivites(r.data.paymentActivity));
                }
                $('#defaultModal').modal('show')
            } else {
                showValidationError(r)
            }
        }
    })
})