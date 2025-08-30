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
  function applyPretermAdjustment(weeksEarly) {
    try {
      const notice = document.createElement("div");
      notice.className = "preterm-alert";
      notice.innerHTML = `
          <h4><i class="fas fa-baby"></i> Preterm Adjustment Applied</h4>
          <p>Your baby was born <strong>${weeksEarly} weeks early</strong>. 
          Vaccines are scheduled based on birth date, not gestational age.</p>
        `;

      const noticeContainer = document.querySelector(".preterm-notice");
      if (noticeContainer) {
        noticeContainer.appendChild(notice);
      }
      const dateCells = document.querySelectorAll(
        ".schedule-table td:nth-child(2)"
      );
      dateCells.forEach((td) => {
        const originalDate = new Date(td.textContent);
        if (!isNaN(originalDate.getTime())) {
          originalDate.setDate(originalDate.getDate() + weeksEarly * 7);
          td.textContent = originalDate.toLocaleDateString("en-US", {
            month: "short",
            day: "numeric",
            year: "numeric",
          });
          td.innerHTML += ' <span class="tag preterm">Adjusted</span>';
        }
      });
    } catch (error) {
      console.error("Preterm adjustment failed:", error);
      alert("Failed to apply preterm adjustment. Please try again.");
    }
  }
});
