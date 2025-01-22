<?php
	namespace DigitalSplash\Cookie;

	use DigitalSplash\Helpers\Settings;
	use DigitalSplash\Models\Tenant;

	class Cookie {
		public bool $withSession;

		private string $prefix;
		private int $time;
		private string $path;
		private string $domain;
		private bool $secure;

		private string $cookieName;

		public function __construct(string $name = '') {
			$this->withSession = false;

			$this->prefix = str_replace([
				' '
			], '_', strtolower(Tenant::getName()));
			$this->time = time() + (60 * 60 * 24 * 30); //30 Days
			$this->path = '/';
			$this->domain = '.' . Tenant::getDomain();
			$this->secure = true;

			$this->cookieName = '';

			if (Settings::$isLocalEnv) {
				$this->prefix .= '_local';
			} elseif (Settings::$isTestEnv) {
				$this->prefix .= '_test';
			}

			if ($name != '') {
				$this->SetName($name);
			}
		}

		public function SetTime(int $time) {
			$this->time = $time;
		}

		public function SetName(string $name) {
			$this->cookieName = $this->prefix . '_' . $name;
		}

		public function SetCookie(string $value = '') {
			setcookie(
				$this->cookieName,
				$value,
				$this->time,
				$this->path,
				$this->domain,
				$this->secure,
				false);

			if ($this->withSession) {
				$_SESSION[$this->cookieName] = $value;
			}
		}

		public function GetCookie() {
			if (isset($_COOKIE[$this->cookieName])) {
				return $_COOKIE[$this->cookieName];
			}

			if ($this->withSession && isset($_SESSION[$this->cookieName])) {
				return $_SESSION[$this->cookieName];
			}

			return null;
		}

		public function ClearCookie() {
			if (isset($_COOKIE[$this->cookieName])) {
				setcookie(
					$this->cookieName,
					null,
					0,
					$this->path,
					$this->domain,
					$this->secure,
					false);
			}

			if ($this->withSession && isset($_SESSION[$this->cookieName])) {
				unset($_SESSION[$this->cookieName]);
			}

			return true;
		}

	}


?>
