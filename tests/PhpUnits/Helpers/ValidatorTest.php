<?php
	namespace DigitalSplash\Tests\Helpers;

	use PHPUnit\Framework\TestCase;
	use DigitalSplash\Exceptions\InvalidEmailException;
	use DigitalSplash\Exceptions\InvalidNumberException;
	use DigitalSplash\Exceptions\InvalidPasswordCharactersException;
	use DigitalSplash\Exceptions\InvalidPasswordLengthException;
	use DigitalSplash\Exceptions\InvalidPhoneNumberException;
	use DigitalSplash\Exceptions\InvalidUsernameCharactersException;
	use DigitalSplash\Exceptions\InvalidUsernameLengthException;
	use DigitalSplash\Helpers\Helper;
	use DigitalSplash\Language\Helpers\Translate;
	use DigitalSplash\Helpers\Validator;

	final class ValidatorTest extends TestCase {

		public function ValidateEmailThrowErrorProvider(): array {
			return [
				[
					InvalidEmailException::class,
					"exception.InvalidEmail",
					"Test Email"
				],
				[
					InvalidEmailException::class,
					"exception.InvalidEmail",
					"test_email@hotmail"
				],
				[
					InvalidEmailException::class,
					"exception.InvalidEmail",
					"test_email@hotmail.c"
				],
				[
					InvalidEmailException::class,
					"exception.InvalidEmail",
					"test_email.com"
				],
			];
		}

		/**
		 * @dataProvider ValidateEmailThrowErrorProvider
		 */
		public function testValidateEmailThrowError(
			$exception,
			string $exceptionMessage,
			string $argument
		): void {
			$this->expectException($exception);
			$this->expectExceptionMessage(Translate::Translate($exceptionMessage));
			Validator::ValidateEmail($argument);
		}

		public function ValidateEmailSuccessProvider(): array {
			return [
				["test_email@hotmail.co"],
				["test_email@hotmail.com"],
				["test_email@hotmail.co.uk"],
				["test_email@hotmail.com.lb"],
			];
		}

		/**
		 * @dataProvider ValidateEmailSuccessProvider
		 */
		public function testValidateEmailSuccess(
			string $argument
		): void {
			$this->assertTrue(
				Validator::ValidateEmail($argument)
			);
		}

		public function ValidatePhoneNumberThrowErrorProvider(): array {
			return [
				[
					InvalidPhoneNumberException::class,
					"exception.InvalidPhoneNumber",
					"Not a Mobile"
				],
				[
					InvalidPhoneNumberException::class,
					"exception.InvalidPhoneNumber",
					"Special Characters !@#$%^&*()_-+="
				],
			];
		}

		/**
		 * @dataProvider ValidatePhoneNumberThrowErrorProvider
		 */
		public function testValidatePhoneNumberThrowError(
			$exception,
			string $exceptionMessage,
			string $argument
		): void {
			$this->expectException($exception);
			$this->expectExceptionMessage(Translate::Translate($exceptionMessage));
			Validator::ValidatePhoneNumber($argument);
		}

		public function ValidatePhoneNumberSuccessProvider(): array {
			return [
				["03/333333"],
				["03-333333"],
				["+961 3 333333"],
			];
		}

		/**
		 * @dataProvider ValidatePhoneNumberSuccessProvider
		 */
		public function testValidatePhoneNumberSuccess(
			string $argument
		): void {
			$this->assertTrue(
				Validator::ValidatePhoneNumber($argument)
			);
		}

		public function ValidateNumberThrowErrorProvider(): array {
			return [
				[
					InvalidNumberException::class,
					"exception.InvalidNumber",
					"Not a NUmber"
				],
				[
					InvalidNumberException::class,
					"exception.InvalidNumber",
					"Special Characters !@#$%^&*()_-+="
				],
				[
					InvalidNumberException::class,
					"exception.InvalidNumber",
					"123456789a"
				],
				[
					InvalidNumberException::class,
					"exception.InvalidNumber",
					"123456789$"
				],
			];
		}

		/**
		 * @dataProvider ValidateNumberThrowErrorProvider
		 */
		public function testValidateNumberThrowError(
			$exception,
			string $exceptionMessage,
			string $argument
		): void {
			$this->expectException($exception);
			$this->expectExceptionMessage(Translate::Translate($exceptionMessage));
			Validator::ValidateNumber($argument);
		}

		public function testValidateNumberSuccess(): void {
			$this->assertTrue(
				Validator::ValidateNumber("0123456789")
			);
		}

		public function testCleanPhoneNumberSuccess(): void {
			$acceptedChars = [
				"-",
				"/",
				"\\",
				",",
				".",
				"|",
				"%20", // encoded space character
			];
			foreach ($acceptedChars AS $char) {
				$this->assertEquals(
					"03333333",
					Validator::CleanPhoneNumber(" 03{$char}333333 ")
				);
			}

			$nonAcceptedChars = [
				":",
				";",
				"*",
			];
			foreach ($nonAcceptedChars AS $char) {
				$this->assertEquals(
					"03{$char}333333",
					Validator::CleanPhoneNumber(" 03{$char}333333 ")
				);
			}
		}

		public function testValidateUsernameLengthBelowMinimumThrowError(): void {
			$this->expectException(InvalidUsernameLengthException::class);
			$this->expectExceptionMessage(Translate::Translate("exception.InvalidUsernameLength"));
			Validator::ValidateUsername(Helper::GenerateRandomKey(5, true, true));
		}

		public function testValidateUsernameLengthAboveMaximumThrowError(): void {
			$this->expectException(InvalidUsernameLengthException::class);
			$this->expectExceptionMessage(Translate::Translate("exception.InvalidUsernameLength"));
			Validator::ValidateUsername(Helper::GenerateRandomKey(21, true, true));
		}

		public function testValidateUsernameCharactersThrowError_01(): void {
			$this->expectException(InvalidUsernameCharactersException::class);
			$this->expectExceptionMessage(Translate::Translate("exception.InvalidUsernameCharacters"));
			Validator::ValidateUsername(Helper::GenerateRandomKey(12, false, false, true));
		}

		public function testValidateUsernameSuccess(): void {
			$this->assertTrue(
				Validator::ValidateUsername("johndoe")
			);
			$this->assertTrue(
				Validator::ValidateUsername("john_doe")
			);
			$this->assertTrue(
				Validator::ValidateUsername("John_Doe")
			);
			$this->assertTrue(
				Validator::ValidateUsername("john_007")
			);
			$this->assertTrue(
				Validator::ValidateUsername("John_Doe_007")
			);
		}

		public function testValidatePasswordLengthBelowMinimumThrowError(): void {
			$this->expectException(InvalidPasswordLengthException::class);
			$this->expectExceptionMessage(Translate::Translate("exception.InvalidPasswordLength"));
			Validator::ValidatePassword(Helper::GenerateRandomKey(5, true, true, true));
		}

		public function testValidatePasswordCharactersThrowError(): void {
			$this->expectException(InvalidPasswordCharactersException::class);
			$this->expectExceptionMessage(Translate::Translate("exception.InvalidPasswordCharacters"));
			Validator::ValidatePassword(Helper::GenerateRandomKey(18, true, true, false));
		}

		public function testValidatePasswordSuccess(): void {
			$this->assertTrue(
				Validator::ValidatePassword("JohnDoe123$%^")
			);
		}

	}
