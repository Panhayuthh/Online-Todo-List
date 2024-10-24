const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});

function checkScreenSize() {
  const sidebar = document.querySelector("#sidebar");
  const screenWidth = window.innerWidth;

  if (screenWidth > 1500) {
    sidebar.classList.add("expand");
  }

  if (screenWidth <= 1500 && sidebar.classList.contains("expand")) {
    sidebar.classList.remove("expand");
  }
}

window.addEventListener("resize", checkScreenSize);

checkScreenSize();
document.addEventListener('DOMContentLoaded', function () {
  const checkboxes = document.querySelectorAll('.complete-task-checkbox');
  
  checkboxes.forEach(function (checkbox) {
      checkbox.addEventListener('change', function () {
          const taskId = this.getAttribute('data-task-id');
          const taskRow = this.closest('tr');
          const taskName = taskRow.querySelector('td:nth-child(2)');
          const isChecked = this.checked;
          const newStatus = isChecked ? 'Completed' : 'In Progress';
          
          fetch('updateTaskStatus.php', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({ taskId: taskId, status: newStatus })
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  if (isChecked) {
                      taskRow.classList.add('table-secondary');
                      taskName.classList.add('text-decoration-line-through');
                  } else {
                      taskRow.classList.remove('table-secondary');
                      taskName.classList.remove('text-decoration-line-through');
                  }
              } else {
                  alert('Failed to update task status.');
              }
          })
          .catch(error => console.error('Error:', error));
      });
  });
});
