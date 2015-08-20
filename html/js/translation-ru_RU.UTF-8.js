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
		case 'Next':
			return 'Следующий';
		case 'First':
			return 'Первый';
		case 'Last':
			return 'Последний';
		case 'Previous':
			return 'Предыдущий';
		case 'Search':
			return 'Поиск';
		case 'activate to sort column ascending':
			return 'активировать для сортировки столбца по возрастанию';
		case 'activate to sort column descending':
			return 'активировать для сортировки столбца по убыванию';
		case 'filtered from _MAX_ total entries':
			return 'отфильтровaнные из _MAX_ всего записей';
		case 'Show _MENU_ entries':
			return 'Показать записи _MENU_';
		case 'Processing...':
			return 'Обработка ...';
		case 'No matching records found':
			return 'Не найдено ни одной записи';
		case 'No data available in table':
			return 'Данные отсутствуют в таблице';
		case 'Showing _START_ to _END_ of _TOTAL_ entries':
			return 'Показано от _START_ до _END_ из _TOTAL_ записей';
		case 'Showing 0 to 0 of 0 entries':
			return 'Показано от 0 до 0 из 0 записей';
		case 'This field is required':
			return 'Заполнение данного поля обязательно';
		case 'Please fix this field':
			return 'Пожалуйста, исправьте это поле';
		case 'Please enter a valid email address':
			return 'Пожалуйста, введите верный адрес электронной почты';
		case 'Please enter a valid URL':
			return 'Пожалуйста, введите верный URL';
		case 'Please enter a valid date':
			return 'Пожалуйста, введите верную дату';
		case 'Please enter a valid date (ISO)':
			return 'Пожалуйста, введите верную дату (ISO)';
		case 'Please enter a valid number':
			return 'Пожалуйста, введите верный номер';
		case 'Please enter only digits':
			return 'Пожалуйста, введите только цифры';
		case 'Please enter a valid credit card number':
			return 'Пожалуйста, введите верный номер кредитной карты';
		case 'Please enter the same value again':
			return 'Пожалуйста, введите то же значение снова';
		case 'Please enter a value with a valid extension':
			return 'Пожалуйста, введите значение с допустимым расширением';
		case 'Please enter no more than {0} characters':
			return 'Пожалуйста, введите не более {0} символов';
		case 'Please enter at least {0} characters':
			return 'Пожалуйста, введите по крайней мере {0} символов';
		case 'Please enter a value between {0} and {1} characters long':
			return 'Пожалуйста, введите значение длиной между {0} и {1} символов';
		case 'Please enter a value between {0} and {1}':
			return 'Пожалуйста, введите значение между {0} и {1}';
		case 'Please enter a value less than or equal to {0}':
			return 'Пожалуйста, введите значение, которое меньше или равно {0}';
		case 'Please enter a value greater than or equal to {0}':
			return 'Пожалуйста, введите значение больше или равно {0}';
		case 'Trying to save... please wait':
		return 'Пожалуйста, подождите';
		case "Couldn't save, sorry!":
			return 'Не удалось сохранить.';
		case "If you have made any changes to this page without clicking the Save button, your changes will be lost.  Are you sure you wish to leave this page?":
			return 'Если вы внесли какие-либо изменения этой страницы без нажатия на кнопку Сохранить, ваши изменения будут потеряны. Вы уверены, что хотите покинуть эту страницу?';
		case "Please save changes.":
			return 'Пожалуйста, сохраните изменения.';
		case "Could not delete, sorry.  The server said:":
			return 'Не удалось удалить. Сообщение с сервера:';	
		case "Are you sure you want to add a region level? A new default location will be created at this level, and all child locations will be added to this default.":
			return 'Вы уверены, что вы хотите добавить уровень региона? Новое местоположение по умолчанию будет создаваться на этом уровне, и все следующие местоположения будут добавляться к этому уровню по умолчанию.';
	}

	return phrase;
}