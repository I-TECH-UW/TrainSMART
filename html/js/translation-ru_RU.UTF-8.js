function tr(phrase) {
	switch (phrase) {
		case 'Remove':
			return 'Удалить';
		case 'Deleting...':
			return 'Удаление...';
		case 'Are you sure you want to remove':
			return 'Вы уверены что хотите удалить';
		case 'Undeleting...':
			return 'Восстановление...';
		case 'Saving...':
			return "Сохранение...";
		case 'Edit':
			return 'Редактировать';
		case 'Delete':
			return 'Удалить';
		case 'Add':
			return 'Добавить';
		case 'Are you sure you want to delete':
			return 'Вы уверены что хотите удалить';
		case 'Pick a Date':
			return 'Выбрать дату';
		case 'No records found.':
			return 'Не найдено ни одной записи';
		case 'Loading...':
			return 'Загрузка...';
		case 'You have not selected a training category for your new title.':
			return "Вы не выбрали категорию тренинга для вашего нового названия.";
		case 'Do you still wish to add a title without a training category associated with it?':
			return 'Вы все еще хотите добавить заголовок без категории тренинга, связанные с ним?';
	}

	return phrase;
}