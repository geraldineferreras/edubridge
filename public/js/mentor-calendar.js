function closeModal() {
  const m = document.getElementById("scheduleModal");
  if (m) m.classList.add("hidden");
}

document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("scheduleModal");
  const form = document.getElementById("scheduleForm");
  const dateInput = document.getElementById("scheduleDate");
  const idInput = document.getElementById("scheduleId");
  const timeInput = document.getElementById("scheduleTime");
  const courseInput = document.getElementById("scheduleCourse");
  const modalTitle = document.getElementById("modalTitle");
  const saveBtn = document.getElementById("saveBtn");

  const monthYearLabel = document.getElementById("currentMonthYear");
  const calendarGrid = document.getElementById("calendarDays");
  const prevBtn = document.getElementById("prevMonth");
  const nextBtn = document.getElementById("nextMonth");

  if (!calendarGrid || !monthYearLabel) return;

  let schedules = [];
  try {
    const dataEl = document.getElementById("calendarData");
    schedules = dataEl && dataEl.dataset.schedules ? JSON.parse(dataEl.dataset.schedules) : [];
  } catch (e) {
    console.error("Failed to parse schedules JSON:", e);
  }

  const routes = window.routes || {};
  const dayNames = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
  let currentDate = new Date();

  function renderCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startDay = firstDay.getDay();

    const monthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];
    monthYearLabel.textContent = `${monthNames[month]} ${year}`;
    calendarGrid.innerHTML = "";

    // day headers
    dayNames.forEach(d => {
      const h = document.createElement("div");
      h.className = "day-name";
      h.textContent = d;
      calendarGrid.appendChild(h);
    });

    // empty slots
    for (let i = 0; i < startDay; i++) {
      const empty = document.createElement("div");
      empty.className = "day empty";
      calendarGrid.appendChild(empty);
    }

    // days
    for (let i = 1; i <= daysInMonth; i++) {
      const dayBox = document.createElement("div");
      dayBox.className = "day";
      const selectedDate = `${year}-${String(month+1).padStart(2,"0")}-${String(i).padStart(2,"0")}`;

      const existingSchedules = schedules.filter(s => s.date === selectedDate);

      const dayNum = document.createElement("div");
      dayNum.className = "day-number";
      dayNum.textContent = i;
      dayBox.appendChild(dayNum);

      existingSchedules.forEach(sch => {
        const schedDiv = document.createElement("div");
        schedDiv.className = "schedule-label";

        const time = (sch.time && sch.time.length >= 5) ? sch.time.slice(0,5) : (sch.time || "");
        const mentorName = sch.mentor ? `${sch.mentor.first_name} ${sch.mentor.last_name}` : "Unknown";

        schedDiv.innerHTML = `${time} - ${sch.course} <br><small>Mentor: ${mentorName}</small>`;
        dayBox.appendChild(schedDiv);
      });

      dayBox.addEventListener("click", () => {
        dateInput.value = selectedDate;

        if (existingSchedules.length > 0) {
          const sch = existingSchedules[0];
          if (idInput) idInput.value = sch.id || "";
          if (timeInput) timeInput.value = (sch.time && sch.time.length >= 5) ? sch.time.slice(0,5) : (sch.time || "");
          if (courseInput) courseInput.value = sch.course || "";

          if (routes.updateTemplate) form.action = routes.updateTemplate.replace('__ID__', sch.id);
          form.method = "POST";

          let methodInput = form.querySelector('input[name="_method"]');
          if (!methodInput) {
            methodInput = document.createElement("input");
            methodInput.type = "hidden";
            methodInput.name = "_method";
            methodInput.value = "PUT";
            form.appendChild(methodInput);
          }

          if (modalTitle) modalTitle.textContent = "Edit Schedule";
          if (saveBtn) saveBtn.textContent = "Update Schedule";
        } else {
          if (idInput) idInput.value = "";
          if (timeInput) timeInput.value = "";
          if (courseInput) courseInput.value = "";

          if (routes.store) form.action = routes.store;
          form.method = "POST";

          const methodInput = form.querySelector('input[name="_method"]');
          if (methodInput) methodInput.remove();

          if (modalTitle) modalTitle.textContent = "Schedule a Session";
          if (saveBtn) saveBtn.textContent = "Save Schedule";
        }

        modal.classList.remove("hidden");
      });

      calendarGrid.appendChild(dayBox);
    }
  }

  if (prevBtn) prevBtn.addEventListener("click", () => { currentDate.setMonth(currentDate.getMonth()-1); renderCalendar(currentDate); });
  if (nextBtn) nextBtn.addEventListener("click", () => { currentDate.setMonth(currentDate.getMonth()+1); renderCalendar(currentDate); });

  renderCalendar(currentDate);

  const closeBtn = document.getElementById("closeModalBtn");
  if (closeBtn) closeBtn.addEventListener("click", closeModal);
});
