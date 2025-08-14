document.addEventListener("DOMContentLoaded", function () {
  const ctx = document.getElementById("progressChart").getContext("2d");
  const progressChart = new Chart(ctx, {
    type: "bar",
    data: {
      labels: ["BCG", "Hep-B", "Pentavalent", "PCV", "MR-1", "MR-2"],
      datasets: [
        {
          label: "Vaccination Completion",
          data: [95, 92, 88, 85, 78, 65],
          backgroundColor: [
            "#4CAF50",
            "#4CAF50",
            "#FFC107",
            "#FFC107",
            "#F44336",
            "#F44336",
          ],
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          max: 100,
        },
      },
    },
  });

  document.querySelectorAll(".btn-approve").forEach((btn) => {
    btn.addEventListener("click", function () {
      const card = this.closest(".request-card");
      card.style.opacity = "0.5";
      setTimeout(() => {
        card.remove();
        updateStats("approve");
      }, 300);
    });
  });

  document.querySelectorAll(".btn-reject").forEach((btn) => {
    btn.addEventListener("click", function () {
      const card = this.closest(".request-card");
      card.style.opacity = "0.5";
      setTimeout(() => {
        card.remove();
        updateStats("reject");
      }, 300);
    });
  });

  document.getElementById("logout-btn").addEventListener("click", function () {
    if (confirm("Are you sure you want to logout?")) {
      alert("Logged out successfully. Redirecting to login...");
    }
  });

  function updateStats(action) {
    const pendingElement = document.querySelector(
      ".stat-card:nth-child(2) .stat-value"
    );
    const currentPending = parseInt(pendingElement.textContent);
    pendingElement.textContent = currentPending - 1;
  }
});
