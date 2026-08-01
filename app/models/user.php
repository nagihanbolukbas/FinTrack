<?php

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // LOGIN
    public function findByEmail($email)
    {
        $query = $this->pdo->prepare("SELECT * FROM users WHERE email=?");
        $query->execute([$email]);

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // REGISTER
    public function emailExists($email)
    {
        $query = $this->pdo->prepare("SELECT id FROM users WHERE email=?");
        $query->execute([$email]);

        return $query->rowCount() > 0;
    }

    public function createUser(
        $first_name,
        $last_name,
        $email,
        $passwordHash,
        $verificationCode,
        $verificationExpiry
    ) {

        $insert = $this->pdo->prepare("
            INSERT INTO users
            (
                first_name,
                last_name,
                email,
                password,
                verification_code,
                verification_expiry,
                is_verified
            )
            VALUES (?,?,?,?,?,?,0)
        ");

        return $insert->execute([
            $first_name,
            $last_name,
            $email,
            $passwordHash,
            $verificationCode,
            $verificationExpiry
        ]);
    
}
public function getByEmail($email)
{
    $query = $this->pdo->prepare("
        SELECT *
        FROM users
        WHERE email=?
    ");

    $query->execute([$email]);

    return $query->fetch(PDO::FETCH_ASSOC);
}

public function verifyUser($id)
{
    $update = $this->pdo->prepare("
        UPDATE users
        SET is_verified=1,
            verification_code=NULL,
            verification_expiry=NULL
        WHERE id=?
    ");

    return $update->execute([$id]);
}
public function verifyResetCode($email, $code)
{
    $query = $this->pdo->prepare("
        SELECT id
        FROM users
        WHERE email=?
        AND reset_code=?
        AND reset_expire > NOW()
    ");

    $query->execute([$email, $code]);

    return $query->fetch(PDO::FETCH_ASSOC);
}
/*forgot-pass*/
public function emailExistsForReset($email)
{
    $query = $this->pdo->prepare("
        SELECT id
        FROM users
        WHERE email=?
    ");

    $query->execute([$email]);

    return $query->rowCount() > 0;
}

public function saveResetCode($email, $resetCode, $resetExpire)
{
    $update = $this->pdo->prepare("
        UPDATE users
        SET reset_code=?,
            reset_expire=?
        WHERE email=?
    ");

    return $update->execute([
        $resetCode,
        $resetExpire,
        $email
    ]);
}

public function getPasswordByEmail($email)
{
    $stmt = $this->pdo->prepare("
        SELECT password
        FROM users
        WHERE email=?
    ");

    $stmt->execute([$email]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updatePassword($email, $hash)
{
    $update = $this->pdo->prepare("
        UPDATE users
        SET password=?,
            reset_code=NULL,
            reset_expire=NULL
        WHERE email=?
    ");

    return $update->execute([
        $hash,
        $email
    ]);
}
}