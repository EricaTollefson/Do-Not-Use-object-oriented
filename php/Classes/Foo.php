<?php
namespace Etollefson\ObjectOriented;

require_once("autoload.php");
require_once(dirname(__DIR__) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * Data for Author class
 *
 * This is an example of what data needs to be included in a database for information on an author, including a username, contact email, and the author's avatar.
 *
 * @author Erica Tollefson <etollefson@cnm.edu>
 * @version 1.0.0
 **/
class Author implements \JsonSerializable {
	use ValidateDate;
	use ValidateUuid;
	/**
	 * id for this Author; this is the primary key
	 * @var Uuid $authorId
	 **/
	private $authorId;
	/**
	 * url of the Author's avatar;
	 * @var string $authorAvatarUrl
	 **/
	private $authorAvatarUrl;
	/**
	 * activation token for this Author; this verifies that the Author is valid and not malicious.
	 * @var string $authorActivationToken
	 **/
	private $authorActivationToken;
	/**
	 * contact email for this Author; this is a unique index
	 * @var string $authorEmail
	 **/
	private $authorEmail;
	/**
	 * hash for this Author email
	 * @var string $authorHash
	 **/
	private $authorHash;
	/**
	 * username for this Author; this is unique
	 * @var string $authorUsername
	 **/
	private $authorUsername;

	/**
	 * constructor for this Author
	 *
	 * @param string|Uuid $newAuthorId id of this Author
	 * @param string|null $newAuthorAvatarUrl url of the author's avatar or null if avatar not created
	 * @param string|null $newAuthorActivationToken string containing the author activation token
	 * @param string $newAuthorEmail unique contact email for author
	 * @param string $newAuthorHash hash for author email
	 * @param string $newAuthorUsername unique username for author
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newAuthorId, string $newAuthorAvatarUrl = null, ?string $newAuthorActivationToken = null, ?string $newAuthorEmail, ?string $newAuthorHash, ?string $newAuthorUsername) {
		try {
			$this->setAuthorId($newAuthorId);
			$this->setAuthorAvatarUrl($newAuthorAvatarUrl);
			$this->setAuthorActivationToken($newAuthorActivationToken);
			$this->setAuthorEmail($newAuthorEmail);
			$this->setAuthorHash($newAuthorHash);
			$this->setAuthorUserName($newAuthorUsername);
		}
			//determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for author id
	 *
	 * @return Uuid value of author id
	 **/
	public function getAuthorId() : Uuid {
		return($this->authorId);
	}

	/**
	 * mutator method for author id
	 *
	 * @param Uuid|string $newAuthorId new value of author id
	 * @throws \RangeException if $newAuthorId is not positive
	 * @throws \TypeError if $newAuthorId is not a uuid or string
	 **/
	public function setAuthorId( $newAuthorId) : void {
		try {
			$uuid = self::validateUuid($newAuthorId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the author id
		$this->authorId = $uuid;
	}

	/**
	 * accessor method for author avatar url
	 *
	 * @return string value of author avatar url
	 **/
	public function getAuthorAvatarUrl() : string {
		return($this->authorAvatarUrl);
	}

	/**
	 * mutator method for author avatar url
	 *
	 * @param string $newAuthorAvatarUrl new value of author avatar url
	 * @throws \RangeException if the author avatar url is greater than 255 characters
	 * @throws \InvalidArgumentException  if the author avatar url is not a string or insecure
	 **/
public function setAuthorAvatarUrl(?string $newAuthorAvatarUrl) : void {
	if($newAuthorAvatarUrl === null) {
		$this->authorAvatarUrl = null;
		return;
	}
	//make sure author avatar url is less than 255 characters
	$newAuthorAvatarUrl = strtolower(trim($newAuthorAvatarUrl));
	if(ctype_xdigit($newAuthorAvatarUrl) === false) {
		throw(new\RangeException("url is not valid"));
	}

	// verify the author avatar url is secure
	$newAuthorAvatarUrl = trim($newAuthorAvatarUrl);
	$newAuthorAvatarUrl = filter_var($newAuthorAvatarUrl, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	if(empty($newAuthorAvatarUrl) === true) {
		throw(new \InvalidArgumentException("author avatar url is empty or insecure"));
	}
	$this->authorAvatarUrl = $newAuthorAvatarUrl;
}

	/**
	 * accessor method for author activation token
	 *
	 * @return string value of author activation token
	 **/
	public function getAuthorActivationToken() : ?string {
		return($this->authorActivationToken);
	}

	/**
	 * mutator method for author activation token
	 *
	 * @param string $newAuthorActivationToken new value of author activation token
	 * @throws \InvalidArgumentException if if the token is not a string or insecure
	 * @throws \RangeException if the token is not exactly 32 characters
	 * @throws \TypeError if the token is not a string
	 **/
	public function setAuthorActivationToken(?string $newAuthorActivationToken) : void {
		if($newAuthorActivationToken === null) {
			$this->authorActivationToken = null;
			return;
		}
		$newAuthorActivationToken = strtolower(trim($newAuthorActivationToken));
		if(ctype_xdigit($newAuthorActivationToken) === false) {
			throw(new\RangeException("activation token is not valid"));
		}
		//make sure author activation token is only 32 characters
		if(strlen($newAuthorActivationToken) !== 32) {
			throw(new\RangeException("author activation token has to be 32 characters"));
		}
		$this->authorActivationToken = $newAuthorActivationToken;
	}

	/**
	 * accessor method for author email
	 *
	 * @return string value of email
	 **/
	public function getAuthorEmail() : string {
		return($this->authorEmail);
	}

	/**
	 * mutator method for author email
	 *
	 * @param string $newAuthorEmail string author email
	 * @throws \InvalidArgumentException if $newAuthorEmail is not a valid email or insecure
	 * @throws \RangeException if $newAuthorEmail is > 128 characters
	 * @throws \TypeError if $newAuthorEmail is not a string
	 **/
	public function setAuthorEmail(?string $newAuthorEmail): void {
		// verify the email is secure
		$newAuthorEmail = trim($newAuthorEmail);
		$newAuthorEmail = filter_var($newAuthorEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newAuthorEmail) === true) {
			throw(new \InvalidArgumentException("email is empty or insecure"));
		}
		// verify the email will fit in the database
		if(strlen($newAuthorEmail) > 128) {
			throw(new \RangeException("email is too large"));
		}
		// store the email
		$this->authorEmail = $newAuthorEmail;
	}
	/**
	 * accessor method for authorHash
	 *
	 * @return string value of hash
	 */
	public function getAuthorHash(): string {
		return $this->authorHash;
	}

	/**
	 * mutator method for profile hash password
	 *
	 * @param string $newAuthorHash
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 97 characters
	 * @throws \TypeError if profile hash is not a string
	 */
	public function setAuthorHash(?string $newAuthorHash): void {
		//enforce that the hash is properly formatted
		$newAuthorHash = trim($newAuthorHash);
		if(empty($newAuthorHash) === true) {
			throw(new \InvalidArgumentException("profile password hash empty or insecure"));
		}
		//enforce the hash is really an Argon hash
		$authorHashInfo = password_get_info($newAuthorHash);
		if($authorHashInfo["algoName"] !== "argon2i") {
			throw(new \InvalidArgumentException("profile hash is not a valid hash"));
		}
		//enforce that the hash is exactly 97 characters.
		if(strlen($newAuthorHash) !== 97) {
			throw(new \RangeException("profile hash must be 97 characters"));
		}
		//store the hash
		$this->authorHash = $newAuthorHash;
	}
	/**
	 * inserts this Tweet into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {

		// create query template
		$query = "INSERT INTO tweet(tweetId,tweetProfileId, tweetContent, tweetDate) VALUES(:tweetId, :tweetProfileId, :tweetContent, :tweetDate)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$formattedDate = $this->tweetDate->format("Y-m-d H:i:s.u");
		$parameters = ["tweetId" => $this->tweetId->getBytes(), "tweetProfileId" => $this->tweetProfileId->getBytes(), "tweetContent" => $this->tweetContent, "tweetDate" => $formattedDate];
		$statement->execute($parameters);
	}


	/**
	 * deletes this Tweet from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {

		// create query template
		$query = "DELETE FROM tweet WHERE tweetId = :tweetId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["tweetId" => $this->tweetId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * updates this Tweet in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {

		// create query template
		$query = "UPDATE tweet SET tweetProfileId = :tweetProfileId, tweetContent = :tweetContent, tweetDate = :tweetDate WHERE tweetId = :tweetId";
		$statement = $pdo->prepare($query);


		$formattedDate = $this->tweetDate->format("Y-m-d H:i:s.u");
		$parameters = ["tweetId" => $this->tweetId->getBytes(),"tweetProfileId" => $this->tweetProfileId->getBytes(), "tweetContent" => $this->tweetContent, "tweetDate" => $formattedDate];
		$statement->execute($parameters);
	}

	/**
	 * gets the Tweet by tweetId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $tweetId tweet id to search for
	 * @return Tweet|null Tweet found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getTweetByTweetId(\PDO $pdo, $tweetId) : ?Tweet {
		// sanitize the tweetId before searching
		try {
			$tweetId = self::validateUuid($tweetId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT tweetId, tweetProfileId, tweetContent, tweetDate FROM tweet WHERE tweetId = :tweetId";
		$statement = $pdo->prepare($query);

		// bind the tweet id to the place holder in the template
		$parameters = ["tweetId" => $tweetId->getBytes()];
		$statement->execute($parameters);

		// grab the tweet from mySQL
		try {
			$tweet = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$tweet = new Tweet($row["tweetId"], $row["tweetProfileId"], $row["tweetContent"], $row["tweetDate"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($tweet);
	}

	/**
	 * gets the Tweet by profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $tweetProfileId profile id to search by
	 * @return \SplFixedArray SplFixedArray of Tweets found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getTweetByTweetProfileId(\PDO $pdo, $tweetProfileId) : \SplFixedArray {

		try {
			$tweetProfileId = self::validateUuid($tweetProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT tweetId, tweetProfileId, tweetContent, tweetDate FROM tweet WHERE tweetProfileId = :tweetProfileId";
		$statement = $pdo->prepare($query);
		// bind the tweet profile id to the place holder in the template
		$parameters = ["tweetProfileId" => $tweetProfileId->getBytes()];
		$statement->execute($parameters);
		// build an array of tweets
		$tweets = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$tweet = new Tweet($row["tweetId"], $row["tweetProfileId"], $row["tweetContent"], $row["tweetDate"]);
				$tweets[$tweets->key()] = $tweet;
				$tweets->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($tweets);
	}

	/**
	 * gets the Tweet by content
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $tweetContent tweet content to search for
	 * @return \SplFixedArray SplFixedArray of Tweets found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getTweetByTweetContent(\PDO $pdo, string $tweetContent) : \SplFixedArray {
		// sanitize the description before searching
		$tweetContent = trim($tweetContent);
		$tweetContent = filter_var($tweetContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($tweetContent) === true) {
			throw(new \PDOException("tweet content is invalid"));
		}

		// escape any mySQL wild cards
		$tweetContent = str_replace("_", "\\_", str_replace("%", "\\%", $tweetContent));

		// create query template
		$query = "SELECT tweetId, tweetProfileId, tweetContent, tweetDate FROM tweet WHERE tweetContent LIKE :tweetContent";
		$statement = $pdo->prepare($query);

		// bind the tweet content to the place holder in the template
		$tweetContent = "%$tweetContent%";
		$parameters = ["tweetContent" => $tweetContent];
		$statement->execute($parameters);

		// build an array of tweets
		$tweets = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$tweet = new Tweet($row["tweetId"], $row["tweetProfileId"], $row["tweetContent"], $row["tweetDate"]);
				$tweets[$tweets->key()] = $tweet;
				$tweets->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($tweets);
	}

	/**
	 * gets all Tweets
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Tweets found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllTweets(\PDO $pdo) : \SPLFixedArray {
		// create query template
		$query = "SELECT tweetId, tweetProfileId, tweetContent, tweetDate FROM tweet";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of tweets
		$tweets = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$tweet = new Tweet($row["tweetId"], $row["tweetProfileId"], $row["tweetContent"], $row["tweetDate"]);
				$tweets[$tweets->key()] = $tweet;
				$tweets->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($tweets);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() : array {
		$fields = get_object_vars($this);

		$fields["tweetId"] = $this->tweetId->toString();
		$fields["tweetProfileId"] = $this->tweetProfileId->toString();

		//format the date so that the front end can consume it
		$fields["tweetDate"] = round(floatval($this->tweetDate->format("U.u")) * 1000);
		return($fields);
	}
}