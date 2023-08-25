<?php
 
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class VerivikasiLogin extends Mailable
{
    use Queueable, SerializesModels;
 
 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $name,$email,$userId;

    public function __construct($name,$email,$userId)
    {
        $this->name = $name;
        $this->email = $email;
        $this->userId = $userId;

    }
    
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('BookingStudio@gmail.com')    
                    ->view('emailku')
                    ->with(
                        [
                            'name' => $this->name, 
                            'email' => $this->email, 
                            'userId' => $this->userId, 

                            
                        ]
                    );
    }

}

