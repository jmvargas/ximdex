<?php

namespace Ximdex\Parsers;
/**
 *  \details &copy; 2011  Open Ximdex Evolution SL [http://www.ximdex.org]
 *
 *  Ximdex a Semantic Content Management System (CMS)
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published
 *  by the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  See the Affero GNU General Public License for more details.
 *  You should have received a copy of the Affero GNU General Public License
 *  version 3 along with Ximdex (see LICENSE file).
 *
 *  If not, visit http://gnu.org/licenses/agpl-3.0.html.
 *
 * @author Ximdex DevTeam <dev@ximdex.com>
 * @version $Revision$
 */
class ParsingMail2News
{

    protected $body;
    protected $parser;
    protected $subject;

    function __construct()
    {

        $this->parser = 'parser1';
        $this->body = '';
        $this->subject = '';
    }

    public function setBody($content = '')
    {

        $this->body = $content;
    }

    public function setSubject($content = '')
    {

        $this->subject = $content;
    }

    public function setParser($val)
    {

        $this->parser = $val;
    }

    /**
     * @return string
     */

    private function getBody()
    {

        return $this->body;
    }

    /**
     * @return string
     */

    private function getSubject()
    {

        return $this->subject;
    }

    /**
     * @return string
     */

    private function getParser()
    {

        return $this->parser;
    }

    /**
     *  Return the data of the new
     * @return array / NULL
     */

    public function getData()
    {

        return call_user_func_array('self::' . $this->getParser(), array());
    }
}
