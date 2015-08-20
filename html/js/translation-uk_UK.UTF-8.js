function tr(phrase) {
	switch (phrase) {
		case 'Remove':
			return 'Видалити';
		case 'Deleting...':
			return 'Видалення ...';
		case 'Are you sure you want to remove':
			return 'Ви впевнені, що хочете видалити';
		case 'Undeleting...':
			return 'Відновлення...';
		case 'Saving...':
			return "Збереження...";		
		case 'Edit':
			return 'Редагувати';
		case 'Delete':
			return 'Видалити ';
		case 'Add':
			return 'Додати';		
		case 'Are you sure you want to delete':
			return 'Ви впевнені, що хочете видалити';
		case 'Pick a Date':
			return 'Вибрати дату';
		case 'No records found.':
			return 'Записів не знайдено.';
		case 'Loading...':
			return 'Завантаження...';
		case 'You have not selected a training category for your new title.':
			return "Ви не обрали категорію тренінгу для вашої нової назви.";
		case 'Do you still wish to add a title without a training category associated with it?':
			return 'Ви все ще хочете додати заголовок без категорії тренінгу, повязаний з ним?';
		case 'Next':
			return 'Наступний';	
		case 'First':
			return 'Перший';
		case 'Last':
			return 'Останній';	
		case 'Previous':
			return 'Попередній';
		case 'Search':
			return 'Пошук';
		case 'activate to sort column ascending':
			return 'активувати для сортування стовпця за зростанням';
		case 'activate to sort column descending':
			return 'активувати для сортування стовпця за спаданням';
		case 'відфільтровані з _MAX_ всього записів':
			return 'отфильтровaнные из _MAX_ всего записей';
		case 'Show _MENU_ entries':
			return 'Показати записи _MENU_';
		case 'Processing...':
			return 'Обробка ...';
		case 'No matching records found':
			return 'Не знайдено жодного запису';
		case 'No data available in table':
			return 'Дані відсутні в таблиці';
		case 'Showing _START_ to _END_ of _TOTAL_ entries':
			return 'Показано від _START до _END_ з _TOTAL_ записів';
		case 'Showing 0 to 0 of 0 entries':
			return 'Показано від 0 до 0 з 0 записів';
		case 'This field is required':
			return 'Заповнення даного поля обовязково';
		case 'Please fix this field':
			return 'Будь ласка, виправте це поле';
		case 'Please enter a valid email address':
			return 'Будь ласка, введіть вірну адресу електронної пошти';
		case 'Please enter a valid URL':
			return 'Будь ласка, введіть вірний URL';
		case 'Будь ласка, введіть вірну дату':
			return 'Пожалуйста, введите верную дату';
		case 'Please enter a valid date (ISO)':
			return 'Будь ласка, введіть вірну дату (ISO)';
		case 'Please enter a valid number':
			return 'Будь ласка, введіть вірний номер';
		case 'Please enter only digits':
			return 'Будь ласка, введіть тільки цифри';
		case 'Please enter a valid credit card number':
			return 'Будь ласка, введіть вірний номер кредитної картки';
		case 'Please enter the same value again':
			return 'Будь ласка, введіть те ж значення знову';
		case 'Please enter a value with a valid extension':
			return 'Будь ласка, введіть значення з допустимим розширенням';
		case 'Please enter no more than {0} characters':
			return 'Будь ласка, введіть не більше {0} символів';
		case 'Please enter at least {0} characters':
			return 'Будь ласка, введіть принаймні {0} символів';
		case 'Please enter a value between {0} and {1} characters long':
			return 'Будь ласка, введіть значення довжиною між {0} та {1} символів';
		case 'Please enter a value between {0} and {1}':
			return 'Будь ласка, введіть значення між {0} та {1}';
		case 'Please enter a value less than or equal to {0}':
			return 'Будь ласка, введіть значення, яке менше або дорівнює {0}';
		case 'Please enter a value greater than or equal to {0}':
			return 'Будь ласка, введіть значення більше або дорівнює {0}';
		case 'Trying to save... please wait':
		return 'Будь ласка зачекайте';
		case "Couldn't save, sorry!":
			return 'Не вдалося зберегти!';
		case "If you have made any changes to this page without clicking the Save button, your changes will be lost.  Are you sure you wish to leave this page?":
			return 'Якщо ви внесли якісь зміни цієї сторінки без натискання на кнопку Зберегти, ваші зміни будуть втрачені. Ви впевнені, що хочете залишити цю сторінку?';
		case "Please save changes.":
			return 'Будь ласка, збережіть зміни.';
		case "Could not delete, sorry.  The server said:":
			return 'Не вдалося видалити. Повідомлення з сервера:';	
		case "Are you sure you want to add a region level? A new default location will be created at this level, and all child locations will be added to this default.":
			return 'Ви впевнені, що ви хочете додати рівень регіону? Нове місце розташування за замовчуванням буде створюватися на цьому рівні, і всі наступні місцезнаходження будуть додаватися до цього рівня за замовчуванням.';
	}

	return phrase;
}