<?php

namespace App\Controllers;

use App\Models\ScormModel;

class ScormPlayer extends BaseController
{
    public function load($scormId)
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return redirect()->to(site_url('login'));
        }

        $scorm = new ScormModel();
        $package = $scorm->package((int) $scormId);
        if (! $package) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('SCORM ' . $scormId);
        }

        return view('scorm/player', [
            'package' => $package,
            'values' => $scorm->initialValues((int) $scormId, $user, $this->session->get('name')),
            'scormId' => (int) $scormId,
        ]);
    }

    public function datamodel($scormId)
    {
        $user = $this->session->get('user');
        if (! is_array($user)) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'message' => 'Unauthenticated']);
        }

        $payload = $this->request->getJSON(true);
        if (! is_array($payload)) {
            $payload = $this->request->getPost();
        }

        $values = is_array($payload['values'] ?? null) ? $payload['values'] : $payload;
        $result = (new ScormModel())->saveValues((int) $scormId, $values, $user);

        return $this->response->setJSON($result);
    }
}
