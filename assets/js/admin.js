const ticketModal = document.getElementById('ticketModal');
const ticketModalClose = document.getElementById('ticketModalClose');
const ticketViewButtons = document.querySelectorAll('.ticket-view');

ticketViewButtons.forEach((button) => {
    button.addEventListener('click', () => {
        document.getElementById('modalTicketId').textContent = button.dataset.ticketId;
        document.getElementById('modalEmployeeName').textContent = button.dataset.employeeName;
        document.getElementById('modalEmployeeId').textContent = button.dataset.employeeId;
        document.getElementById('modalDepartment').textContent = button.dataset.department;
        document.getElementById('modalIssueType').textContent = button.dataset.issueType;
        document.getElementById('modalPriority').textContent = button.dataset.priority;
        document.getElementById('modalContact').textContent = button.dataset.contact;
        document.getElementById('modalStatus').textContent = button.dataset.status;
        document.getElementById('modalCreated').textContent = button.dataset.created;
        document.getElementById('modalDescription').textContent = button.dataset.description;

        ticketModal.classList.add('active');
    });
});

ticketModalClose.addEventListener('click', () => {
    ticketModal.classList.remove('active');
});

ticketModal.addEventListener('click', (event) => {
    if (event.target === ticketModal) {
        ticketModal.classList.remove('active');
    }
});