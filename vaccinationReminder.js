document.addEventListener("DOMContentLoaded", function () {
  const isDetailsPage = document.querySelector(".vaccine-header");
  const isEpiSchedule = document.querySelector(".epi-schedule");
  if (isDetailsPage) {
    const downloadBtn = document.getElementById("download-certificate");
    if (downloadBtn) {
      downloadBtn.addEventListener("click", handleDownloadCertificate);
    }
    const findCenterBtn = document.getElementById("find-center");
    if (findCenterBtn) {
      findCenterBtn.addEventListener("click", handleFindCenter);
    }
  }
});
