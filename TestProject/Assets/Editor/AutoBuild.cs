using UnityEngine;
using UnityEditor;
using System.Collections;
using System.IO;
using System.Diagnostics;

/// <summary>
/// 自动打包
/// </summary>
public class AutoBuild : Editor
{
    /// <summary>
    /// 导出路径
    /// </summary>
    private static string m_strExportPath = "test";

    /// <summary>
    /// 是否打表和打协议
    /// </summary>
    private static string m_strBuildTableAndMsg = "F";

    /// <summary>
    /// 是否打资源
    /// </summary>
    private static string m_strBuildAssets = "F";

    /// <summary>
    /// 是否打版本
    /// </summary>
    private static string m_strBuildClient = "F";

    /// <summary>
    /// LOG文件目录
    /// </summary>
    private static string LOG_FILE_PATH = @"C:\Program Files\phpStudy\WWW\autobuild\msg_build.txt";

    /// <summary>
    /// Gets the levels.
    /// </summary>
    /// <returns>The levels.</returns>
    private static string[] GetLevels()
    {
        return new string[] { "Assets/Client.unity" };
    }

    /// <summary>
    /// 解析参数
    /// </summary>
    private static void AnalysisParameters()
    {
        foreach (string arg in System.Environment.GetCommandLineArgs())
        {
            if (arg.StartsWith("build_out_path"))
            {
                string[] args = arg.Split('-');
#if UNITY_IPHONE
                if (args.Length > 1)
                {
                    m_strExportPath = args[1];
                }
#elif UNITY_WEBPLAYER
				if (args.Length > 1)
				{
					m_strExportPath = args[1];
				}
				if (args.Length > 2)
				{
					m_strBuildAssets = arg.Split("-"[0])[2];
				}
				if (args.Length > 3)
				{
					m_strBuildTableAndMsg = arg.Split("-"[0])[3];
				}
				if (args.Length > 4)
				{
					m_strBuildClient = arg.Split("-"[0])[4];
				}
#endif

                return;
            }
        }
        m_strExportPath = "test";
        m_strBuildAssets = "F";
        m_strBuildTableAndMsg = "F";
        m_strBuildClient = "F";
    }

    /// <summary>
    /// Builds the IO.
    /// </summary>
    public static void BuildIOS()
    {
        LOG_FILE_PATH = Application.dataPath + "/../lai.txt";

        LogAppend("hello");

        AnalysisParameters();

        LogAppend(m_strExportPath);

        PlayerSettings.SetScriptingDefineSymbolsForGroup(BuildTargetGroup.iPhone, "AUTO_VERSION;IOS_PRE");

        LogAppend("hello2:" + PlayerSettings.GetScriptingDefineSymbolsForGroup(BuildTargetGroup.iPhone));

        BuildPipeline.BuildPlayer(GetLevels(), m_strExportPath, BuildTarget.iPhone, BuildOptions.None);//last par must be None

        LogAppend("hello3");
    }

    /// <summary>
    /// 打Web版
    /// </summary>
    public static void BuildWeb()
    {
        AnalysisParameters();

        if (m_strBuildTableAndMsg.Equals("T"))
        {
            LogAppend("BuildTableAndMsg Start");
            //TablePacker.AutoPack();
            LogAppend("BuildTableAndMsg Finish");
        }

        if (m_strBuildAssets.Equals("T"))
        {
            LogAppend("GenerateLevelConfigFile Start");
            //VersionInfoGenerator.GenerateLevelConfigFile();
            LogAppend("GenerateLevelConfigFile Finish");
        }

        if (m_strBuildClient.Equals("T"))
        {
            LogAppend("BuildClient Start");

            PlayerSettings.SetScriptingDefineSymbolsForGroup(BuildTargetGroup.WebPlayer, "AUTO_VERSION");

            BuildPipeline.BuildPlayer(GetLevels(), m_strExportPath, BuildTarget.WebPlayer, BuildOptions.AcceptExternalModificationsToPlayer);

            LogAppend("BuildClient Finish");
        }
    }

    /// <summary>
    /// <para>追加log</para>
    /// <para>datetime+logMsg</para>
    /// </summary>
    /// <param name="logMsg"></param>
    private static bool LogAppend(string logMsg)
    {
        bool bRet = true;

        FileMode fileMode = FileMode.Create;

        if (File.Exists(LOG_FILE_PATH))
        {
            fileMode = FileMode.Append;
        }

        try
        {
            FileStream fileStream = new FileStream(LOG_FILE_PATH, fileMode);
            StreamWriter streamWriter = new StreamWriter(fileStream);

            streamWriter.WriteLine(string.Format("{0} {1}", System.DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss"), logMsg));

            streamWriter.Close();
        }
        catch (System.Exception ex)
        {
            bRet = false;
        }

        return bRet;
    }
}
