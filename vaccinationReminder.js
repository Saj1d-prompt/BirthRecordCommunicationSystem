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
  function handleFindCenter() {
    try {
      console.log("Finding nearby EPI centers...");
      alert(
        "Opening map with nearby EPI centers (simulated). In a real app, this would use Google Maps API."
      );
    } catch (error) {
      console.error("EPI center lookup failed:", error);
      alert("Failed to load EPI centers. Please check your connection.");
    }
  }
});
