<?php
 /**
 * @copyright Copyright (C) 2010 Tailored Solutions. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by Tailored Solutions - ijoomer.com
 *
 * ijoomer can be downloaded from www.ijoomer.com
 * ijoomer is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with ijoomer; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
class ijoomer_helper()
{
	function encryptKey($Str_Message)
	{
	    $Len_Str_Message=strlen($Str_Message);
	    $Str_Encrypted_Message="";
	    for ($Position = 0;$Position<$Len_Str_Message;$Position++)
	    {
	        $Key_To_Use = (($Len_Str_Message+$Position)+1);

	        $Key_To_Use = (255+$Key_To_Use) % 255;
	        $Byte_To_Be_Encrypted = SUBSTR($Str_Message, $Position, 1);
	        $Ascii_Num_Byte_To_Encrypt = ORD($Byte_To_Be_Encrypted);
	        $Xored_Byte = $Ascii_Num_Byte_To_Encrypt ^ $Key_To_Use;  //xor operation
	        $Encrypted_Byte = CHR($Xored_Byte);
	        $Str_Encrypted_Message .= $Encrypted_Byte;

	    }
	    $result = base64_encode($Str_Encrypted_Message);
	    $result = str_replace("+"," ",$result);
	    return $result;
	}

	function decryptKey($Str_Message)
	{
		$Str_Message = base64_decode($Str_Message);
	    $Len_Str_Message=strlen($Str_Message);
	    $Str_Encrypted_Message="";
	    for ($Position = 0;$Position<$Len_Str_Message;$Position++)
	    {
	        $Key_To_Use = (($Len_Str_Message+$Position)+1);

	        $Key_To_Use = (255+$Key_To_Use) % 255;
	        $Byte_To_Be_Encrypted = SUBSTR($Str_Message, $Position, 1);
	        $Ascii_Num_Byte_To_Encrypt = ORD($Byte_To_Be_Encrypted);
	        $Xored_Byte = $Ascii_Num_Byte_To_Encrypt ^ $Key_To_Use;  //xor operation
	        $Encrypted_Byte = CHR($Xored_Byte);
	        $Str_Encrypted_Message .= $Encrypted_Byte;
	    }
	    return $Str_Encrypted_Message;
	} 
}
?>