<?php
/**
 * Created by PhpStorm.
 * User: tarask
 * Date: 5/6/18
 * Time: 6:41 AM
 */

namespace TSK\WebFileEditorBundle\Command;



use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Yaml\Yaml;

class GenerateCredentialsCommand extends Command
{
    private $container;

    protected function configure()
    {
        $this
            ->setName('TSK:generate:credential')
            ->setDefinition(array(
                new InputArgument('usesecret', InputArgument::REQUIRED, 'The username'),
                new InputArgument('code', InputArgument::REQUIRED, 'Google Code')
            ))
            ->setDescription('Get google credential.')
            ->setHelp('This command allows you to generate google credential ...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($input->getArgument('usesecret') && $input->getArgument('code')){
            $output->writeln([
                'Your credential is generate to ',
                '/config/google_auth.yml'
            ]);
            $authCode = $input->getArgument('code');
            $accessToken = $this->container->get('google.client')->fetchAccessTokenWithAuthCode($authCode);

            print_r($accessToken);
            $yaml = Yaml::dump(
                [
                    'file_editor' => [
                        'google' => [
                            'token' => $accessToken
                        ]
                    ]
                ]
            );
            file_put_contents($this->getApplication()->getKernel()->getProjectDir().'/config/google_auth.yml', $yaml);
            $command = $this->getApplication()->find('cache:clear');
            $arguments = array(
                'command' => 'cache:clear'
            );
            $greetInput = new ArrayInput($arguments);
            $command->run($greetInput, $output);
        }

    }

    public function initialize(InputInterface $input, OutputInterface $output){
        $this->container = $this->getApplication()->getKernel()->getContainer();
        $questions = array();
        if (!$input->getArgument('usesecret')) {
            $question = new Question('Make sure the client_secret.json file is in config directory :');
            $question->setValidator(function ($usesecret) {
                $projectDir = $this->getApplication()->getKernel()->getProjectDir();
                if (!file_exists($projectDir.'/config/client_secret.json')) {
                    throw new \Exception($projectDir.'/config/client_secret.json'.' does not exist');
                }
                return true;
            });
            $questions['usesecret'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $authUrl = $this->container->get('google.client')->createAuthUrl();
        $output->writeln([
            'get th code on this url : ',
            $authUrl
        ]);

        $questions = array();
        if (!$input->getArgument('code')) {
            $question = new Question('Paste the google code for get token : ');
            $question->setValidator(function ($code) {
                if(!$code){
                    throw new \Exception('Code can\'t be null');
                }
                return $code;
            });
            $questions['code'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}