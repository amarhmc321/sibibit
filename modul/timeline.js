$(document).ready(function () {
    loadTimeline();
});

function loadTimeline() {

    $.ajax({
        url: "controller/process/aksiTimeLine.php?action=read",
        type: "POST",
        dataType: "json",

        success: function (response) {


            const data = response;
            $("#tgl-penyaluran").val(data.step4_start);

            $(".step").each(function () {

                const step = $(this);
                const stepNumber = step.data("step");

                const startDateString =
                    data[`step${stepNumber}_start`];

                const endDateString =
                    data[`step${stepNumber}_end`];

                if (!startDateString || !endDateString) {
                    return;
                }

                step.attr("data-start", startDateString);
                step.attr("data-end", endDateString);

                step.find(".step-date").text(
                    formatTanggal(
                        startDateString,
                        endDateString
                    )
                );

                setStatus(
                    step,
                    startDateString,
                    endDateString
                );

            });

        },

        error: function (xhr, status, error) {

            console.error(error);

            Popup.error(
                "Gagal!",
                "Gagal mengambil data timeline",
                3000
            );

        }
    });
}


function formatTanggal(start, end) {

    const startDate = new Date(start + "T00:00:00");
    const endDate = new Date(end + "T00:00:00");

    const bulan = [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember"
    ];

    const startDay = String(startDate.getDate()).padStart(2, "0");
    const endDay = String(endDate.getDate()).padStart(2, "0");

    const startMonth = bulan[startDate.getMonth()];
    const endMonth = bulan[endDate.getMonth()];

    const startYear = startDate.getFullYear();
    const endYear = endDate.getFullYear();

    if (startYear === endYear && startMonth === endMonth) {
        return `${startDay} ${startMonth} - ${endDay} ${endYear}`;
    }

    if (startYear === endYear) {
        return `${startDay} ${startMonth} - ${endDay} ${endMonth} ${endYear}`;
    }

    return `${startDay} ${startMonth} ${startYear} - ${endDay} ${endMonth} ${endYear}`;
}


function setStatus(step, startDateString, endDateString) {

    const currentDate = new Date();

    const startDate =
        new Date(startDateString + "T00:00:00");

    const endDate =
        new Date(endDateString + "T23:59:59");

    const statusBadge = step.find(".step-status");

    step.removeClass(
        "belum-selesai sedang-berlangsung"
    );

    statusBadge.removeClass(
        "belum-selesai sedang-berlangsung"
    );

    if (currentDate > endDate) {

        statusBadge.text("Selesai");

    }

    else if (
        currentDate >= startDate &&
        currentDate <= endDate
    ) {

        step.addClass("sedang-berlangsung");

        statusBadge.addClass("sedang-berlangsung");

        statusBadge.text("Sedang Berlangsung");

    }

    else {

        step.addClass("belum-selesai");

        statusBadge.addClass("belum-selesai");

        statusBadge.text("Belum Selesai");

    }
}

