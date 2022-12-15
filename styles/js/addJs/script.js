/* JQuery Load to slowly to call tooltip(). So call tooltip() after Jquery loading time. */
setTimeout(function () {
  $("[data-bs-toggle='tooltip']").tooltip();
  const tooltipTriggerList = document.querySelectorAll(
    "[data-bs-toggle='tooltip']"
  );
  const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
  );
}, 5450 * 1.33); // Estimated time for loading JQuery & Network delation
