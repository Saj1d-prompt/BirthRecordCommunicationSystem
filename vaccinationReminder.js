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
  if (isEpiSchedule) {
    const isPreterm = false;
    const weeksEarly = 4;
    if (isPreterm) {
      applyPretermAdjustment(weeksEarly);
    }
  }
  function handleDownloadCertificate() {
    try {
      console.log("Generating vaccination certificate...");
      alert(
        "Certificate downloaded (simulated). In a real app, this would generate a PDF."
      );
    } catch (error) {
      console.error("Certificate download failed:", error);
      alert("Failed to generate certificate. Please try again.");
    }
  }
});
