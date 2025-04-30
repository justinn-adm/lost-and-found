document.addEventListener("DOMContentLoaded", () => {
	const buttons = document.querySelectorAll('a');
	const mainContent = document.getElementById('main-content');

	buttons.forEach(button => {
		button.addEventListener('click', () => {
			const active = document.querySelector('.active');
			active.classList.toggle('active');
			button.classList.toggle('active');
			const doc = button.getAttribute('id') + '.php';
			mainContent.style.transform = 'translateY(100%)';


			setTimeout(() => {
				mainContent.src = doc;
				void mainContent.offsetHeight;

				setTimeout(() => {
					mainContent.style.transform = 'translateY(0%)';
				}, 50);
			}, 300);
		});
	});
});
