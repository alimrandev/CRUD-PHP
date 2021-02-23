document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".confirm").forEach((conf) => {
    conf.addEventListener("click", (e) => {
      let del = confirm("ar you sure to delete this data");
      if (!del) {
        e.preventDefault();
      }
    });
  });
});
