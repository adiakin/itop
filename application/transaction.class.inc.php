<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * This class records the pending "transactions" corresponding to forms that have not been
 * submitted yet, in order to prevent double submissions. When created a transaction remains valid
 * until the user's session expires
 * @package     iTop
 */


class privUITransaction
{
	/**
	 * Create a new transaction id, store it in the session and return its id
	 * @param void
	 * @return int The identifier of the new transaction
	 */
	public static function GetNewTransactionId()
	{
		if (!isset($_SESSION['transactions']))
		{
				$_SESSION['transactions'] = array();
		}
		// Strictly speaking, the two lines below should be grouped together
		// by a critical section
		// sem_acquire($rSemIdentified);
		$id = str_replace(array('.', ' '), '', microtime()); //1 + count($_SESSION['transactions']);
		$_SESSION['transactions'][$id] = true;
		// sem_release($rSemIdentified);
		
		return (string)$id;
	}

	/**
	 * Check whether a transaction is valid or not and (optionally) remove the valid transaction from
	 * the session so that another call to IsTransactionValid for the same transaction id
	 * will return false
	 * @param int $id Identifier of the transaction, as returned by GetNewTransactionId
	 * @param bool $bRemoveTransaction True if the transaction must be removed
	 * @return bool True if the transaction is valid, false otherwise
	 */	
	public static function IsTransactionValid($id, $bRemoveTransaction = true)
	{
		$bResult = false;
		if (isset($_SESSION['transactions']))
		{
			// Strictly speaking, the eight lines below should be grouped together
			// inside the same critical section as above
			// sem_acquire($rSemIdentified);
			if (isset($_SESSION['transactions'][$id]))
			{
				$bResult = true;
				if ($bRemoveTransaction)
				{
					unset($_SESSION['transactions'][$id]);
				}
			}
			// sem_release($rSemIdentified);
		}
		return $bResult;
	}
	
	/**
	 * Removes the transaction specified by its id
	 * @param int $id The Identifier (as returned by GetNewTranscationId) of the transaction to be removed.
	 * @return void
	 */
	public static function RemoveTransaction($id)
	{
		if (isset($_SESSION['transactions']))
		{
			// Strictly speaking, the three lines below should be grouped together
			// inside the same critical section as above
			// sem_acquire($rSemIdentified);
			if (isset($_SESSION['transactions'][$id]))
			{
				unset($_SESSION['transactions'][$id]);
			}
			// sem_release($rSemIdentified);
		}		
	}
}
?>
