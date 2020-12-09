<?php
namespace Avido\Smtpeter\Tests\Endpoints;

use Avido\Smtpeter\Exceptions\SmtpeterException;
use Avido\Smtpeter\Resources\Template;
use Avido\Smtpeter\Tests\TestCase;
use Illuminate\Support\Collection;

class TemplatesTest extends TestCase
{

    /** @test */
    public function templates_list()
    {
        $templates = $this->client->templates->list();
        $this->assertInstanceOf(Collection::class, $templates);
        $this->assertInstanceOf(Template::class, $templates->first());
    }

    /** @test */
    public function get_template_by_id()
    {
        $id = 4;
        $template = $this->client->templates->get($id);
        $this->assertInstanceOf(Template::class, $template);
        $this->assertEquals($id, $template->id);
    }
}
